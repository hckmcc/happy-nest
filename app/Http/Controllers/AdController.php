<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdController extends Controller
{
    public function showMyAds()
    {
        $categories = Category::with('children.children') // загружаем два уровня вложенности
        ->whereNull('parent_category_id')
            ->get();

        $activeAds = Ad::query()  // добавляем query()
            ->where('user_id', auth()->id())
            ->where('is_completed', false)
            ->with('category')
            ->latest()
            ->get();

        $completedAds = Ad::query()
            ->where('user_id', auth()->id())
            ->where('is_completed', true)
            ->with('category')
            ->latest()
            ->get();

        return view('ads.myAds', compact('categories','activeAds', 'completedAds'));
    }

    public function showAd(Ad $ad)
    {
        $categories = Category::with('children.children') // загружаем два уровня вложенности
        ->whereNull('parent_category_id')
            ->get();
        // Увеличиваем счетчик просмотров
        $ad->increment('views');

        // Получаем похожие объявления
        $similarAds = Ad::where('category_id', $ad->category_id)
            ->where('id', '!=', $ad->id)
            ->where('is_completed', false)
            ->latest()
            ->take(4)
            ->get();

        return view('ads.adPage', compact('categories', 'ad', 'similarAds'));
    }
    public function showCreateAdForm()
    {
        $categories = Category::all();
        return view('ads.createAd', compact('categories'));
    }

    public function createAd(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'address' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'photo' => 'required|image|max:2048', // максимум 2MB
        ]);

        $ad = new Ad();
        $ad->name = $validated['name'];
        $ad->description = $validated['description'];
        $ad->price = $validated['price'];
        $ad->address = $validated['address'];
        $ad->category_id = $validated['category_id'];
        $ad->user_id = auth()->id();

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('ads', 'public');
            $ad->photo = $path;
        }

        $ad->save();

        return redirect()->route('my_ads', $ad)
            ->with('success', 'Объявление успешно создано!');
    }
    public function complete(Ad $ad)
    {
        // Проверяем, что объявление принадлежит текущему пользователю
        if ($ad->user_id !== auth()->id()) {
            abort(403);
        }

        $ad->update([
            'is_completed' => true
        ]);

        return back()->with('success', 'Объявление помечено как завершенное');
    }
    public function republish(Ad $ad)
    {
        // Проверяем, что объявление принадлежит текущему пользователю
        if ($ad->user_id !== auth()->id()) {
            abort(403);
        }

        $ad->update([
            'is_completed' => false
        ]);

        return back()->with('success', 'Объявление помечено как активное');
    }
    public function edit(Ad $ad)
    {
        // Проверка прав доступа
        if ($ad->user_id !== auth()->id()) {
            abort(403);
        }

        $categories = Category::all();
        return view('ads.editAd', compact('ad', 'categories'));
    }

    public function update(Request $request, Ad $ad)
    {
        // Проверка прав доступа
        if ($ad->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'address' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'photo' => 'required|image|max:2048', // максимум 2MB
        ]);

        // Если загружено новое фото
        if ($request->hasFile('photo')) {
            // Удаляем старое фото
            Storage::disk('public')->delete($ad->photo);

            // Сохраняем новое
            $path = $request->file('photo')->store('ads', 'public');
            $validated['photo'] = $path;
        }

        $ad->update($validated);

        return redirect()->route('ad.show', $ad)
            ->with('success', 'Объявление успешно обновлено');
    }

    public function delete(Ad $ad)
    {
        // Проверка прав доступа
        if ($ad->user_id !== auth()->id()) {
            abort(403);
        }

        // Удаляем фото
        if ($ad->photo) {
            Storage::disk('public')->delete($ad->photo);
        }

        $ad->delete();

        return redirect()->route('my_ads')
            ->with('success', 'Объявление успешно удалено');
    }
}

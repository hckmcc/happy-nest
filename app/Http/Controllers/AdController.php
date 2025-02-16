<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\Category;
use App\Models\PromotionType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdController extends Controller
{
    public function showMyAds()
    {
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

        return view('ads.myAds', compact('activeAds', 'completedAds'));
    }

    public function showAd(Ad $ad)
    {
        // Увеличиваем счетчик просмотров
        if (auth()->id() !== $ad->user_id)
        {
            $ad->increment('views');
        }

        // Получаем похожие объявления
        $similarAds = Ad::where('category_id', $ad->category_id)
            ->where('id', '!=', $ad->id)
            ->where('is_completed', false)
            ->latest()
            ->take(4)
            ->get();

        return view('ads.adPage', compact( 'ad', 'similarAds'));
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

        if ($request->from === 'admin') {
            return redirect()->route('admin.ads')
                ->with('success', 'Объявление успешно удалено');
        }

        return redirect()->route('my_ads')
            ->with('success', 'Объявление успешно удалено');
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
            'photo' => 'nullable|image|max:2048', // максимум 2MB
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

    public function delete(Ad $ad, Request $request)
    {
        // Проверка прав доступа
        if (!($ad->user_id === auth()->id() or auth()->user()->hasRole('admin'))) {
            abort(403);
        }

        // Удаляем фото
        if ($ad->photo) {
            Storage::disk('public')->delete($ad->photo);
        }

        $ad->delete();

        if ($request->from === 'admin') {
            return redirect()->route('admin.ads')
                ->with('success', 'Объявление успешно удалено');
        }

        return redirect()->route('my_ads')
            ->with('success', 'Объявление успешно удалено');
    }
    public function showAdPromotePage(Ad $ad)
    {
        if ($ad->user_id !== auth()->id()) {
            abort(403);
        }
        $promotionTypes = PromotionType::all();
        return view('ads.adPromotePage', compact('ad', 'promotionTypes'));
    }
}

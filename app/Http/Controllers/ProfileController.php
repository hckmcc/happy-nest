<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function showProfile()
    {
        $categories = Category::with('children.children') // загружаем два уровня вложенности
        ->whereNull('parent_category_id')
            ->get();
        return view('profile', compact('categories'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        // Валидация
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:255',
            'photo' => 'nullable|image|max:2048', // максимум 2MB
            'current_password' => 'required_with:password|current_password:web',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // Обновление основной информации
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'];

        // Обработка загрузки аватара
        if ($request->hasFile('photo')) {
            // Удаление старого аватара
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }

            // Сохранение нового аватара
            $path = $request->file('photo')->store('avatars', 'public');
            $user->photo = $path;
        }

        // Обновление пароля
        if (isset($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('profile')
            ->with('success', 'Профиль успешно обновлен');
    }
}

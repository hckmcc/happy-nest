<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function add(Request $request)
    {
        $validated = $request->validate([
            'seller_id' => 'required|exists:users,id',
            'ad_id' => 'required|exists:ads,id',
            'rate' => 'required|integer|min:1|max:5',
            'text' => 'string|min:3'
        ]);

        // Добавляем ID текущего пользователя как автора отзыва
        $validated['buyer_id'] = auth()->id();

        Review::create($validated);

        return back()->with('success', 'Отзыв успешно добавлен');
    }
}

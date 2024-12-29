<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\Category;
use App\Models\Review;
use App\Models\User;

class SellerController extends Controller
{
    public function showSeller(User $user)
    {
        $categories = Category::with('children.children') // загружаем два уровня вложенности
        ->whereNull('parent_category_id')
            ->get();
        $seller = User::query()  // Добавляем query()
        ->withCount(['ads', 'reviews'])
            ->withAvg('reviews', 'rate')
            ->findOrFail($user->id);

        $reviews = $seller->reviews()
            ->with('buyer')
            ->latest()
            ->get();

        // Загружаем объявления с пагинацией
        $ads = $seller->ads()
            ->where('is_completed', 'false')
            ->latest()
            ->paginate(12);

        return view('seller.sellerPage', compact('seller', 'categories', 'reviews', 'ads'));
    }
}

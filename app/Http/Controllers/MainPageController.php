<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\Category;

class MainPageController extends Controller
{
    public function index()
    {
        $categories = Category::with('children.children') // загружаем два уровня вложенности
        ->whereNull('parent_category_id')
            ->get();
        $ads = Ad::with(['category', 'user'])->latest()->take(8)->where('is_completed', 'false')->get();

        // Возвращаем view с объявлениями
        return view('main', compact('ads', 'categories'));
    }
}

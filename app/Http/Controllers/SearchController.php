<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        // Получаем все категории для формы фильтрации
        $categories = Category::all();

        // Начинаем строить запрос
        $query = Ad::query()
            ->where('is_completed', 'false');

        // Поиск по названию
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function (Builder $query) use ($searchTerm) {
                $query->where('name', 'ilike', "%{$searchTerm}%")
                    ->orWhere('description', 'ilike', "%{$searchTerm}%");
            });
        }

        // Фильтр по категории
        if ($request->filled('category')) {
            $query->where('category_id', $request->input('category'));
        }

        // Фильтр по цене
        if ($request->filled('price_from')) {
            $query->where('price', '>=', $request->input('price_from'));
        }

        if ($request->filled('price_to')) {
            $query->where('price', '<=', $request->input('price_to'));
        }

        // Сортировка
        $sort = $request->input('sort', 'newest'); // По умолчанию сортируем по новизне

        switch ($sort) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        // Получаем результаты с пагинацией
        $ads = $query->paginate(12)->withQueryString();

        // Возвращаем view с данными
        return view('ads.search', compact('ads', 'categories'));
    }
}

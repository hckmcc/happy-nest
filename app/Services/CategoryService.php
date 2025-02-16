<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CategoryService
{
    const CACHE_KEY = 'categories';
    const CACHE_TIME = 3600;

    public function getAllCategories()
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TIME, function () {
            Log::info('Cache miss - loading from database');
            return Category::with('children.children')->get();
        });
    }

    public function clearCategoriesCache()
    {
        Cache::forget(self::CACHE_KEY);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\Category;
use App\Services\CategoryService;

class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }
    public function showCategoriesList()
    {
        $categories = $this->categoryService->getAllCategories();
        return view('categories.categoriesList', compact('categories'));
    }
    public function showCategory(Category $category)
    {
        $promotedAds = Ad::query()
            ->where('category_id', $category->id)
            ->whereHas('promotions')
            ->with('category')
            ->latest()
            ->get();
        $regularAds = Ad::query()
            ->where('category_id', $category->id)
            ->whereDoesntHave('promotions')
            ->with('category')
            ->latest()
            ->get();
        return view('categories.categoryPage', compact('promotedAds','regularAds', 'category'));
    }
}

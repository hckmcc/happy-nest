<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\Category;

class AdController extends Controller
{
    public function showAds()
    {
        $ads = Ad::all();
        return view('admin.ads.ads', compact('ads'));
    }

    public function showAdPage(Ad $ad)
    {
        $reviews = $ad->reviews()
            ->with('buyer')
            ->latest()
            ->get();
        return view('admin.ads.adPage', compact('ad', 'reviews'));
    }
    public function showAdCreatePage()
    {
        $categories = Category::all();
        return view('admin.ads.createAd', compact('categories'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\Category;
use App\Models\Favourite;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class FavouritesController extends Controller
{
    public function showFavourites()
    {
        $categories = Category::with('children.children') // загружаем два уровня вложенности
        ->whereNull('parent_category_id')
            ->get();
        $ads = Ad::whereHas('favourites', function($query) {
            $query->where('user_id', auth()->id());
        })->get();

        // Возвращаем view с объявлениями
        return view('favourites', compact('ads', 'categories'));
    }
    public function add(Request $request, $adId)
    {
        $user = auth()->user();

        // Проверяем, есть ли уже это объявление в избранном
        $favourite = Favourite::where('user_id', $user->id)
            ->where('ad_id', $adId)
            ->first();

        if ($favourite) {
            // Если уже есть в избранном - удаляем
            $favourite->delete();
            $status = 'removed';
        } else {
            // Если нет - добавляем
            Favourite::create([
                'user_id' => $user->id,
                'ad_id' => $adId
            ]);
            $status = 'added';
        }

        return response()->json([
            'status' => $status,
            'message' => $status === 'added' ? 'Added to favourites' : 'Removed from favourites'
        ]);
    }
}

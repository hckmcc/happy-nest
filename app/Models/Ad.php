<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'price',
        'address',
        'category_id',
        'user_id',
        'photo',
        'is_completed',
        'views'
    ];

    protected $casts = [
        'completed_at' => 'datetime',
        'is_completed' => 'boolean',
        'price' => 'float',
        'views' => 'integer'
    ];
    public function isFavouritedBy(?User $user): bool
    {
        if (!$user) {
            return false;
        }

        return $this->favourites()
            ->where('user_id', $user->id)
            ->exists();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function favourites()
    {
        return $this->hasMany(Favourite::class, 'ad_id');
    }
    public function reviews()
    {
        return $this->hasMany(Review::class, 'ad_id');
    }
    public function promotions()
    {
        return $this->belongsToMany(PromotionType::class, 'ad_promotions', 'ad_id', 'promotion_id')->withTimestamps();
    }
    public function hasPromotion()
    {
        return $this->promotions()->count() !== 0;
    }
}

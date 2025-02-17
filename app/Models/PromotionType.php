<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromotionType extends Model
{
    protected $fillable = ['name', 'description', 'price'];

    public function ads()
    {
        return $this->belongsToMany(Ad::class, 'ad_promotions', 'promotion_id', 'ad_id');
    }
}

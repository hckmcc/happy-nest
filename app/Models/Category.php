<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_category_id',
        'name',
        'icon'
    ];
    public function ads()
    {
        return $this->hasMany(Ad::class);
    }
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_category_id');
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_category_id');
    }

    // Получить все категории с вложенной иерархией
    public static function getTree()
    {
        return static::with('children')
            ->whereNull('parent_category_id')
            ->get();
    }
    protected static function boot()
    {
        parent::boot();

        static::saved(function () {
            Cache::forget('categories');
        });

        static::deleted(function () {
            Cache::forget('categories');
        });
    }
}

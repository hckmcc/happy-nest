<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'photo'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }
    protected $appends = ['rating'];

    public function getRatingAttribute()
    {
        // Безопасно получаем значение рейтинга
        return round($this->reviews_avg_rate ?? 0, 1);
    }
    public function reviews()
    {
        return $this->hasMany(Review::class, 'seller_id');
    }

    public function ads()
    {
        return $this->hasMany(Ad::class, 'user_id');
    }
    public function favourites()
    {
        return $this->hasMany(Favourite::class, 'user_id');
    }
}

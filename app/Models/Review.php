<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;
    protected $fillable = [
        'seller_id',
        'buyer_id',
        'ad_id',
        'rate',
        'text',
    ];

    protected $casts = [
        'rate' => 'integer'
    ];

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }
    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function ad()
    {
        return $this->belongsTo(Ad::class);
    }

}

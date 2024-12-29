<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChatMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'seller_id',
        'buyer_id',
        'ad_id',
        'message',
        'message_author_id'
    ];

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }
    public function messageAuthor()
    {
        return $this->belongsTo(User::class, 'message_author_id');
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function ad()
    {
        return $this->belongsTo(Ad::class);
    }

    // Вспомогательный метод для получения собеседника
    public function otherUser()
    {
        if (auth()->id() === $this->seller_id) {
            return $this->buyer;
        }
        return $this->seller;
    }
}

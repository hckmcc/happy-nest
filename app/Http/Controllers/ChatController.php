<?php

namespace App\Http\Controllers;

use App\Jobs\SendEmailJob;
use App\Jobs\SendChatNotificationEmail;
use App\Jobs\SendReportToYouGile;
use App\Mail\NewMessageNotificationEmail;
use App\Models\Ad;
use App\Models\Category;
use App\Models\ChatMessage;
use App\Models\User;
use App\Services\RabbitMQService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class ChatController extends Controller
{
    private RabbitMQService $rabbitMQService;
    public function __construct(RabbitMQService $rabbitMQService)
    {
        $this->rabbitMQService = $rabbitMQService;
    }
    public function showMyChats()
    {
        $userId = auth()->id();
        $categories = Category::with('children.children') // загружаем два уровня вложенности
        ->whereNull('parent_category_id')
            ->get();

        // Получаем список уникальных чатов с последними сообщениями
        $chats = ChatMessage::query()
            ->select(
                'ad_id',
                DB::raw('MAX(id) as last_message_id'),
                DB::raw('MAX(created_at) as last_message_date'),
                'seller_id',
                'buyer_id'
            )
            ->where('seller_id', $userId)
            ->orWhere('buyer_id', $userId)
            ->groupBy('ad_id', 'seller_id', 'buyer_id')
            ->orderBy('last_message_date', 'desc')
            ->with(['seller:id,name,photo', 'buyer:id,name,photo', 'ad:id,name'])
            ->get()
            ->map(function ($chat) use ($userId) {
                $otherUser = $chat->seller_id === $userId ? $chat->buyer : $chat->seller;

                $lastMessage = ChatMessage::find($chat->last_message_id);

                return [
                    'ad' => $chat->ad,
                    'other_user' => $otherUser,
                    'last_message' => $lastMessage->message,
                    'last_message_date' => $lastMessage->created_at,
                    'ad_id' => $chat->ad_id,
                    'seller_id' => $chat->seller_id,
                    'buyer_id' => $chat->buyer_id
                ];
            });

        return view('chats.myChats', compact('chats', 'categories'));
    }

    // Показ конкретного чата
    public function showChat($adId, $otherUserId)
    {
        $userId = auth()->id();
        $categories = Category::with('children.children') // загружаем два уровня вложенности
        ->whereNull('parent_category_id')
            ->get();

        $messages = ChatMessage::query()  // Добавляем query()
        ->where('ad_id', $adId)
            ->where(function($query) use ($userId, $otherUserId) {
                $query->where(function($q) use ($userId, $otherUserId) {
                    $q->where('seller_id', $userId)
                        ->where('buyer_id', $otherUserId);
                })->orWhere(function($q) use ($userId, $otherUserId) {
                    $q->where('seller_id', $otherUserId)
                        ->where('buyer_id', $userId);
                });
            })
            ->with(['seller:id,name,photo', 'buyer:id,name,photo'])
            ->orderBy('created_at', 'asc')
            ->get();

        $ad = Ad::findOrFail($adId);
        $otherUser = User::findOrFail($otherUserId);

        return view('chats.chatPage', compact('messages', 'categories','ad', 'otherUser'));
    }

    // Отправка сообщения
    public function add(Request $request)
    {
        $validated = $request->validate([
            'ad_id' => 'required|exists:ads,id',
            'message' => 'required|string|max:1000',
            'seller_id' => 'required|exists:users,id',
            'buyer_id' => 'required|exists:users,id',
            'message_author_id' => 'required|exists:users,id',
        ]);

        $message = new ChatMessage();
        $message->ad_id = $validated['ad_id'];
        $message->message = $validated['message'];
        $message->seller_id = $validated['seller_id'];
        $message->buyer_id = $validated['buyer_id'];
        $message->message_author_id = $validated['message_author_id'];
        $message->save();

        SendChatNotificationEmail::dispatch($message)
            ->onQueue('chatNotification');

        return response()->json($message);
    }
}

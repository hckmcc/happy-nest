<?php

namespace App\Jobs;

use App\Mail\NewMessageNotificationEmail;
use App\Models\ChatMessage;
use App\Models\Report;
use App\Services\YouGileService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendChatNotificationEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private ChatMessage $message;

    public function __construct(ChatMessage $message)
    {
        $this->message = $message;
        $this->queue = 'chatNotification';
    }

    public function handle(YouGileService $youGileService)
    {
        try {
            $recipient = $this->message->message_author_id === $message->buyer_id ? $message->seller : $message->buyer;
            Mail::to($this->data['recipientEmail'])->send(new NewMessageNotificationEmail($this->data));
        } catch (\Exception $e) {
            Log::error("Failed to send message: " . $e->getMessage());
            throw $e;
        }
    }

    public function failed(\Exception $exception)
    {
        Log::error("Job failed for email: " . $exception->getMessage());
    }
}

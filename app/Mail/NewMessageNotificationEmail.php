<?php

namespace App\Mail;

use App\Models\ChatMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewMessageNotificationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public array $message;
    public function __construct(array $message)
    {
        $this->message = $message;
    }

    public function build()
    {
        return $this->view('mailTemplates.mail')
            ->subject('Новое сообщение')
            ->with([
                'sender' => $this->message['messageFrom'],
                'ad' => $this->message['adName'],
                'text' => $this->message['messageText'],
            ]);
    }
}

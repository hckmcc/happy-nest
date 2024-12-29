<?php

namespace App\Mail;

use App\Models\ChatMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeEmail extends Mailable
{
    use Queueable, SerializesModels;

    public array $user;
    public function __construct(array $user)
    {
        $this->user = $user;
    }

    public function build()
    {
        return $this->view('mailTemplates.welcomeLetter')
            ->subject('Добро пожаловать!')
            ->with([
                'userName' => $this->user['recipientName'],
            ]);
    }
}

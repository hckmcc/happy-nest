<?php

namespace App\Console\Commands;

use App\Mail\NewMessageNotificationEmail;
use App\Services\RabbitMQService;
use Illuminate\Console\Command;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use App\Mail\TestEmail;
use Illuminate\Support\Facades\Mail;

class ConsumeEmailQueue extends Command
{
    protected $signature = 'rabbitmq:consume-emails
    {--queue=chatNotification : Название очереди}
    {--className=App\Mail\NewMessageNotificationEmail : Название класса}';
    protected $description = 'Consume email queue from RabbitMQ';
    private RabbitMQService $rabbitMQService;

    public function __construct(RabbitMQService $rabbitMQService)
    {
        parent::__construct();
        $this->rabbitMQService = $rabbitMQService;
    }

    public function handle()
    {
        $queue = $this->option('queue');
        $className = $this->option('className');

        $callback = function ($msg) use ($className) {
            try {
                $data = json_decode($msg->body, true);
                Mail::to($data['recipientEmail'])->send(new $className($data));
                $this->info(" [x] Email sent to " . $data['recipientEmail']);
            } catch (\Exception $e) {
                $this->error(" [x] Error sending email: " . $e->getMessage());
            }
        };

        $this->info("Starting consumer for queue: {$queue}");
        $this->rabbitMQService->consume($queue, $callback);
    }
}

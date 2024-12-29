<?php
require_once __DIR__ . '/vendor/autoload.php';

use App\Http\Controllers\MailController;
use App\Mail\NewMessageNotificationEmail;
use App\Mail\TestEmail;
use Illuminate\Support\Facades\Mail;
use PhpAmqpLib\Connection\AMQPStreamConnection;
$connection = new AMQPStreamConnection('rabbitmq', 5672, 'rmuser', 'rmpassword');
$channel = $connection->channel();

$channel->queue_declare('hello', false, false, false, false);

echo " [*] Waiting for messages. To exit press CTRL+C\n";

$callback = function ($msg) {
    $data = json_decode($msg->body, true);
    Mail::to($data['recipientEmail'])->send(new TestEmail());
};

$channel->basic_consume('hello', '', false, true, false, false, $callback);



try {
    $channel->consume();
} catch (\Throwable $exception) {
    echo $exception->getMessage();
}

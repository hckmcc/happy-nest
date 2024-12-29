<?php

namespace App\Services;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQService
{
    private $channel;
    public function __construct()
    {
        $connection = new AMQPStreamConnection('rabbitmq', 5672, 'rmuser', 'rmpassword');
        $this->channel = $connection->channel();
    }

    public function send(array $data, string $queue)
    {
        $this->channel->queue_declare($queue, false, false, false, false);

        $msg = new AMQPMessage(json_encode($data));
        $this->channel->basic_publish($msg, '', $queue);
    }
    public function consume(string $queue, callable $callback)
    {
        $this->channel->queue_declare($queue, false, false, false, false);

        $this->channel->basic_consume($queue, '', false, true, false, false, $callback);

        while ($this->channel->is_consuming()) {
            try {
                $this->channel->wait();
            } catch (\Exception $e) {
                echo $e->getMessage();
                break;
            }
        }

        $this->channel->close();
    }
}

<?php

namespace App\Console\Commands;

use App\Mail\NewMessageNotificationEmail;
use App\Models\Report;
use App\Services\RabbitMQService;
use App\Services\YouGileService;
use Illuminate\Console\Command;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use App\Mail\TestEmail;
use Illuminate\Support\Facades\Mail;

class ConsumeReportQueue extends Command
{
    protected $signature = 'rabbitmq:consume-reports
    {--queue=report : Название очереди}';
    protected $description = 'Consume report queue from RabbitMQ';
    private YouGileService $youGileService;
    private RabbitMQService $rabbitMQService;

    public function __construct(YouGileService $youGileService, RabbitMQService $rabbitMQService)
    {
        parent::__construct();
        $this->youGileService = $youGileService;
        $this->rabbitMQService = $rabbitMQService;
    }

    public function handle()
    {
        $queue = $this->option('queue');

        $callback = function ($msg){
            try {
                $data = json_decode($msg->body, true);
                $reportId = $data['reportId'];
                $report = Report::find($reportId);
                if (!$report) {
                    throw new \Exception("Report not found");
                }
                $reportData =
                    ['title' => "Жалоба: {$reportId}",
                        'description' => "User: {$report->user_id},
                                Ad: {$report->ad_id},
                                Reason: {$report->reason}"
                    ];
                $this->youGileService->sendReport($reportData);
                $this->info(" [x] Report sent");
            } catch (\Exception $e) {
                $this->error(" [x] Error sending report: " . $e->getMessage());
            }
        };

        $this->info("Starting consumer for queue: {$queue}");
        $this->rabbitMQService->consume($queue, $callback);
    }
}

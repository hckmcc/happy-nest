<?php

namespace App\Jobs;

use App\Models\Report;
use App\Services\YouGileService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendReportToYouGile implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private int $reportId;

    public function __construct(int $reportId)
    {
        $this->reportId = $reportId;
        $this->queue = 'reports';
    }

    public function handle(YouGileService $youGileService)
    {
        try {
            $report = Report::find($this->reportId);

            if (!$report) {
                throw new \Exception("Report not found: {$this->reportId}");
            }

            $reportData = [
                'title' => "Жалоба: {$this->reportId}",
                'description' => "User: {$report->user_id},
                        Ad: {$report->ad_id},
                        Reason: {$report->reason}"
            ];

            $youGileService->sendReport($reportData);

            Log::info("Report {$this->reportId} successfully sent to YouGile");
        } catch (\Exception $e) {
            Log::error("Failed to send report {$this->reportId}: " . $e->getMessage());
            throw $e;
        }
    }

    public function failed(\Exception $exception)
    {
        Log::error("Job failed for report {$this->reportId}: " . $exception->getMessage());
    }
}

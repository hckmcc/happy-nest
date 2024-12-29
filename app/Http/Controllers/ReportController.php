<?php

namespace App\Http\Controllers;

use App\Jobs\SendReportToYouGile;
use App\Models\Report;
use App\Services\RabbitMQService;
use App\Services\YouGileService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    private RabbitMQService $rabbitMQService;

    public function __construct(RabbitMQService $rabbitMQService)
    {
        $this->rabbitMQService = $rabbitMQService;
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required',
            'ad_id' => 'required',
            'reason' => 'required|string',
        ]);
        $report = Report::query()->create([
            'user_id' => $validated['user_id'],
            'ad_id' => $validated['ad_id'],
            'reason' => $validated['reason']
        ]);

        SendReportToYouGile::dispatch($report->id)
            ->onQueue('reports');

        //return response()->json(['message' => 'Report queued for processing']);

        return back()->with('success');
    }
}

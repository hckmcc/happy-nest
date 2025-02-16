<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;

class ReportController extends Controller
{
    public function showReports()
    {
        $reports = Report::all();
        return view('admin.reports.reports', compact('reports'));
    }
    public function deleteReport(Report $report)
    {
        $report->delete();

        return redirect()->route('admin.reports')
            ->with('success', 'Жалоба успешно удалена');
    }
}

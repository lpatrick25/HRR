<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\ResortTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResortReportController extends Controller
{
    public function index(Request $request)
    {
        $reportType = $request->input('report_type', 'daily');
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);

        // Get available years from transactions
        $availableYears = ResortTransaction::selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        // Get available months for the selected year
        $availableMonths = ResortTransaction::selectRaw('MONTH(created_at) as month')
            ->whereYear('created_at', $year)
            ->distinct()
            ->orderBy('month')
            ->pluck('month');

        $query = ResortTransaction::query()
            ->where('status', 'Completed')
            ->selectRaw('DATE(created_at) as date, COUNT(*) as transaction_count, SUM(total_amount) as total_revenue')
            ->groupBy('date');

        if ($reportType === 'daily') {
            $query->whereDate('created_at', now()->toDateString());
        } elseif ($reportType === 'monthly') {
            $query->whereMonth('created_at', $month)
                ->whereYear('created_at', $year);
        } elseif ($reportType === 'yearly') {
            $query->whereYear('created_at', $year);
        }

        $reports = $query->get();

        return view('reports.resort', compact('reports', 'reportType', 'month', 'year', 'availableYears', 'availableMonths'));
    }
}

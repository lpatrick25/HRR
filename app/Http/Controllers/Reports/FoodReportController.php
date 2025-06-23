<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\FoodTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FoodReportController extends Controller
{
    public function index(Request $request)
    {
        $reportType = $request->input('report_type', 'daily');
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);

        $availableYears = FoodTransaction::selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        $availableMonths = FoodTransaction::selectRaw('MONTH(created_at) as month')
            ->whereYear('created_at', $year)
            ->distinct()
            ->orderBy('month')
            ->pluck('month');

        $query = FoodTransaction::query()
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

        return view('reports.food', compact('reports', 'reportType', 'month', 'year', 'availableYears', 'availableMonths'));
    }
}

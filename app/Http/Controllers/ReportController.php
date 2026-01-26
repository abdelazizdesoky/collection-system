<?php

namespace App\Http\Controllers;

use App\Models\CustomerAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReportController extends Controller
{
    /**
     * Display the reports dashboard.
     */
    public function index(Request $request): View
    {
        $year = $request->input('year', now()->year);
        
        // Monthly Report (for selected year)
        $monthlyData = CustomerAccount::active() // Filter active only
            ->select(
                DB::raw('MONTH(date) as month'),
                DB::raw('SUM(debit) as total_debit'),
                DB::raw('SUM(credit) as total_credit')
            )
            ->whereYear('date', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Yearly Report (All time)
        $yearlyData = CustomerAccount::active()
            ->select(
                DB::raw('YEAR(date) as year'),
                DB::raw('SUM(debit) as total_debit'),
                DB::raw('SUM(credit) as total_credit')
            )
            ->groupBy('year')
            ->orderBy('year', 'desc')
            ->get();

        return view('reports.index', compact('monthlyData', 'yearlyData', 'year'));
    }
}

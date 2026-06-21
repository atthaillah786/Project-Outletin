<?php

namespace App\Http\Controllers;

use App\Models\Outlet;
use App\Models\FinancialReport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FranchisorFinancialController extends Controller
{
    // Compare today's total income per outlet for the authenticated franchisor
    public function todayPerOutlet(Request $request)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'franchisor') {
            abort(403, 'Akses hanya untuk franchisor.');
        }

        $today = Carbon::today();

        // Get outlets that belong to franchisor's brands and are approved
        $outlets = Outlet::whereHas('brand', function ($q) use ($user) {
                $q->where('franchisor_id', $user->user_id)->where('status', 'approved');
            })
            ->withSum(['financialReports as today_total_items' => function ($q) use ($today) {
                $q->whereDate('report_date', $today);
            }], 'total_items')
            ->withSum(['financialReports as today_total_income' => function ($q) use ($today) {
                $q->whereDate('report_date', $today);
            }], 'total_income')
            ->withSum(['financialReports as today_total_expense' => function ($q) use ($today) {
                $q->whereDate('report_date', $today);
            }], 'total_expense')
            ->orderBy('outlet_name')
            ->get();

        $labels = $outlets->pluck('outlet_name')->toArray();

        // Income data untuk bar chart
        $incomeData = $outlets->map(fn($o) => (float) ($o->today_total_income ?? 0))->toArray();
        // Expense data untuk bar chart (warna berbeda)
        $expenseData = $outlets->map(fn($o) => (float) ($o->today_total_expense ?? 0))->toArray();

        $tableRows = $outlets->map(fn($o) => [
            'outlet_name' => $o->outlet_name,
            'total_items' => (int) ($o->today_total_items ?? 0),
            'total_income' => (float) ($o->today_total_income ?? 0),
            'total_expense' => (float) ($o->today_total_expense ?? 0),
            'total_profit' => (float) (($o->today_total_income ?? 0) - ($o->today_total_expense ?? 0)),
        ])->toArray();

        return view('dashboard.franchisor_outlets_today', compact('labels', 'incomeData', 'expenseData', 'tableRows'));
    }

    /**
     * Daily transaction comparison across all outlets for a selected date range.
     * Shows grouped bar chart (income) + line chart (expense) per outlet per day.
     * Data source: financial_reports table
     */
    public function dailyPerOutlet(Request $request)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'franchisor') {
            abort(403, 'Akses hanya untuk franchisor.');
        }

        // Date range: default last 7 days
        $startDate = $request->query('start_date')
            ? Carbon::parse($request->query('start_date'))->startOfDay()
            : Carbon::today()->subDays(6);
        $endDate = $request->query('end_date')
            ? Carbon::parse($request->query('end_date'))->endOfDay()
            : Carbon::today()->endOfDay();

        if ($startDate->greaterThan($endDate)) {
            [$startDate, $endDate] = [$endDate, $startDate];
        }

        // Get all approved outlets belonging to this franchisor's brands
        $outlets = Outlet::whereHas('brand', function ($q) use ($user) {
                $q->where('franchisor_id', $user->user_id)->where('status', 'approved');
            })
            ->orderBy('outlet_name')
            ->get();

        // Build date labels
        $days = $startDate->diffInDays($endDate) + 1;
        $dateLabels = [];
        $dateKeys = [];
        for ($i = 0; $i < $days; $i++) {
            $current = $startDate->copy()->addDays($i);
            $dateLabels[] = $current->format('d M');
            $dateKeys[] = $current->toDateString();
        }

        // Fetch all financial reports for these outlets in the date range
        $outletIds = $outlets->pluck('outlet_id');
        $reports = FinancialReport::whereIn('outlet_id', $outletIds)
            ->whereBetween('report_date', [$startDate->toDateString(), $endDate->toDateString()])
            ->orderBy('report_date')
            ->get();

        // Build income & expense datasets per outlet
        $incomeDatasets = [];
        $expenseDatasets = [];

        foreach ($outlets as $index => $outlet) {
            // Build lookup keyed by date string to avoid Carbon cast comparison issues
            $reportLookup = [];
            foreach ($reports->where('outlet_id', $outlet->outlet_id) as $r) {
                $reportLookup[$r->report_date->toDateString()] = $r;
            }

            $incomeData = [];
            $expenseData = [];

            foreach ($dateKeys as $key) {
                if (isset($reportLookup[$key])) {
                    $incomeData[] = (float) $reportLookup[$key]->total_income;
                    $expenseData[] = (float) $reportLookup[$key]->total_expense;
                } else {
                    $incomeData[] = 0;
                    $expenseData[] = 0;
                }
            }

            // Distinct colors per outlet — 8 unique palettes
            $palettes = [
                ['bg' => 'rgba(54, 162, 235, 0.75)',  'border' => 'rgba(54, 162, 235, 1)'],
                ['bg' => 'rgba(255, 99, 132, 0.75)',  'border' => 'rgba(255, 99, 132, 1)'],
                ['bg' => 'rgba(255, 206, 86, 0.75)',  'border' => 'rgba(255, 206, 86, 1)'],
                ['bg' => 'rgba(75, 192, 192, 0.75)',  'border' => 'rgba(75, 192, 192, 1)'],
                ['bg' => 'rgba(153, 102, 255, 0.75)', 'border' => 'rgba(153, 102, 255, 1)'],
                ['bg' => 'rgba(255, 159, 64, 0.75)',  'border' => 'rgba(255, 159, 64, 1)'],
                ['bg' => 'rgba(99, 255, 132, 0.75)',  'border' => 'rgba(99, 255, 132, 1)'],
                ['bg' => 'rgba(255, 99, 255, 0.75)',  'border' => 'rgba(255, 99, 255, 1)'],
            ];
            $p = $palettes[$index % count($palettes)];

            $incomeDatasets[] = [
                'label' => $outlet->outlet_name,
                'data' => $incomeData,
                'backgroundColor' => $p['bg'],
                'borderColor' => $p['border'],
                'borderWidth' => 1,
            ];

            $expenseDatasets[] = [
                'label' => $outlet->outlet_name,
                'data' => $expenseData,
                'borderColor' => $p['border'],
                'backgroundColor' => $p['bg'],
                'borderWidth' => 2,
                'tension' => 0.3,
                'pointRadius' => 4,
                'pointHoverRadius' => 6,
                'fill' => false,
            ];
        }

        // Summary totals per outlet for the period
        $outletTotals = [];
        foreach ($outlets as $outlet) {
            $outletReports = $reports->where('outlet_id', $outlet->outlet_id);
            $income = (float) $outletReports->sum('total_income');
            $expense = (float) $outletReports->sum('total_expense');
            $items = (int) $outletReports->sum('total_items');
            $profit = $income - $expense;

            $outletTotals[] = [
                'outlet_id' => $outlet->outlet_id,
                'outlet_name' => $outlet->outlet_name,
                'total_items' => $items,
                'total_income' => $income,
                'total_expense' => $expense,
                'total_profit' => $profit,
            ];
        }

        // Sort by income descending
        usort($outletTotals, fn($a, $b) => $b['total_income'] <=> $a['total_income']);

        $grandIncome = array_sum(array_column($outletTotals, 'total_income'));
        $grandExpense = array_sum(array_column($outletTotals, 'total_expense'));
        $grandProfit = $grandIncome - $grandExpense;

        return view('dashboard.franchisor_daily_transactions', compact(
            'dateLabels',
            'incomeDatasets',
            'expenseDatasets',
            'outletTotals',
            'grandIncome',
            'grandExpense',
            'grandProfit',
            'startDate',
            'endDate',
            'outlets'
        ));
    }
}
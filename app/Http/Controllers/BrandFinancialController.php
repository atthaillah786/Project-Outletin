<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Outlet;
use App\Models\FinancialReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;

class BrandFinancialController extends Controller
{
    public function index($brandId, Request $request)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'franchisor') {
            abort(403, 'Akses hanya untuk pemilik brand.');
        }

        $brand = Brand::findOrFail($brandId);
        if ($brand->franchisor_id !== $user->user_id) {
            abort(403, 'Brand tidak dimiliki oleh Anda.');
        }

        $outletId = $request->query('outlet_id');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

$start = $startDate ? Carbon::parse($startDate)->startOfDay() : Carbon::now()->startOfMonth();
        $end = $endDate ? Carbon::parse($endDate)->endOfDay() : Carbon::now()->endOfMonth();


        if ($start->greaterThan($end)) {
            [$start, $end] = [$end, $start];
        }

        $outletsQuery = Outlet::where('brand_id', $brandId);
        if ($outletId) {
            $outletsQuery->where('outlet_id', $outletId);
        }
        $outlets = $outletsQuery->get();

        $reports = FinancialReport::whereIn('outlet_id', $outlets->pluck('outlet_id'))
            ->whereBetween('report_date', [$start->toDateString(), $end->toDateString()])
            ->orderBy('report_date')
            ->get();

        // Daily labels for chart
        $days = $start->diffInDays($end) + 1;
        $labels = [];
        $dateKeys = [];
        for ($i = 0; $i < $days; $i++) {
            $current = $start->copy()->addDays($i);
            $labels[] = $current->format('j');
            $dateKeys[] = $current->toDateString();
        }

        // Totals per outlet (for ranking)
        $outletTotals = [];
        foreach ($outlets as $outlet) {
            $outletReports = $reports->where('outlet_id', $outlet->outlet_id);
            $income = (float) $outletReports->sum('total_income');
            $expense = (float) $outletReports->sum('total_expense');

            $outletTotals[] = [
                'outlet_id' => $outlet->outlet_id,
                'outlet_name' => $outlet->outlet_name,
                'total_income' => $income,
                'total_expense' => $expense,
                'total_profit' => $income - $expense,
            ];
        }

        usort($outletTotals, function ($a, $b) {
            return $b['total_income'] <=> $a['total_income'];
        });

        $totalIncome = (float) $reports->sum('total_income');
        $totalExpense = (float) $reports->sum('total_expense');
        $totalProfit = $totalIncome - $totalExpense;

        // Chart datasets per outlet
        $datasets = [];
        $colors = [
            'rgba(54, 162, 235, 0.8)',
            'rgba(255, 99, 132, 0.8)',
            'rgba(255, 206, 86, 0.8)',
            'rgba(75, 192, 192, 0.8)',
            'rgba(153, 102, 255, 0.8)',
            'rgba(255, 159, 64, 0.8)',
            'rgba(99, 255, 132, 0.8)',
        ];

        foreach ($outlets as $index => $outlet) {
            // Build lookup keyed by date string to avoid Carbon cast comparison issues
            $reportLookup = [];
            foreach ($reports->where('outlet_id', $outlet->outlet_id) as $r) {
                $reportLookup[$r->report_date->toDateString()] = $r;
            }
            $data = [];
            foreach ($dateKeys as $key) {
                $data[] = isset($reportLookup[$key]) ? (float) $reportLookup[$key]->total_income : 0;
            }

            $color = $colors[$index % count($colors)];
            $datasets[] = [
                'label' => $outlet->outlet_name,
                'data' => $data,
                'backgroundColor' => $color,
                'borderColor' => $color,
            ];
        }

// Pass outlets list for rendering select filter.
        // If outletId query is provided, the list will only include that outlet.
        $outletsList = $outlets;

        return view('financial.brand_report', compact(
            'brand',
            'labels',
            'datasets',
            'outletTotals',
            'totalIncome',
            'totalExpense',
            'totalProfit',
            'start',
            'end',
            'outletId',
            'outletsList'
        ));

    }

    public function download($brandId, Request $request)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'franchisor') {
            abort(403, 'Akses hanya untuk pemilik brand.');
        }

        $brand = Brand::findOrFail($brandId);
        if ($brand->franchisor_id !== $user->user_id) {
            abort(403, 'Brand tidak dimiliki oleh Anda.');
        }

        $outletId = $request->query('outlet_id');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $start = $startDate ? Carbon::parse($startDate)->startOfDay() : Carbon::now()->startOfMonth();
        $end = $endDate ? Carbon::parse($endDate)->endOfDay() : Carbon::now()->endOfMonth();

        if ($start->greaterThan($end)) {
            [$start, $end] = [$end, $start];
        }

        $outletsQuery = Outlet::where('brand_id', $brandId);
        if ($outletId) {
            $outletsQuery->where('outlet_id', $outletId);
        }
        $outlets = $outletsQuery->get();

        $reports = FinancialReport::whereIn('outlet_id', $outlets->pluck('outlet_id'))
            ->whereBetween('report_date', [$start->toDateString(), $end->toDateString()])
            ->orderBy('report_date')
            ->get();

        $filename = 'financial_report_brand_' . $brandId . '_' . date('Ymd') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($reports, $outlets) {
            $outletMap = $outlets->keyBy('outlet_id');
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['report_date', 'outlet_id', 'outlet_name', 'total_income', 'total_expense']);

            foreach ($reports as $r) {
                $outlet = $outletMap[$r->outlet_id] ?? null;
                fputcsv($handle, [
                    $r->report_date,
                    $r->outlet_id,
                    $outlet ? $outlet->outlet_name : '',
                    $r->total_income,
                    $r->total_expense,
                ]);
            }

            fclose($handle);
        };

        return Response::stream($callback, 200, $headers);
    }
}


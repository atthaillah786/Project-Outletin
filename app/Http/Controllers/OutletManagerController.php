<?php

namespace App\Http\Controllers;

use App\Models\Outlet;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OutletManagerController extends Controller
{
    // Show 7-day trend and daily transaction list for the outlet managed by the authenticated franchisee
    public function weeklyTrend(Request $request, $outletId)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'franchise') {
            abort(403, 'Akses hanya untuk pemilik outlet.');
        }

        $outlet = Outlet::where('outlet_id', $outletId)
            ->where('franchise_id', $user->user_id)
            ->firstOrFail();

        $end = Carbon::today();
        $start = Carbon::today()->subDays(6);

        $transactions = Transaction::where('outlet_id', $outlet->outlet_id)
            ->whereBetween('transaction_date', [$start->toDateString(), $end->toDateString()])
            ->orderBy('transaction_date')
            ->get();

        $grouped = $transactions->groupBy(function ($t) {
            return $t->transaction_date->toDateString();
        });

        $labels = [];
        $incomeData = [];
        $tableRows = [];

        for ($d = $start->copy(); $d->lte($end); $d->addDay()) {
            $dateKey = $d->toDateString();
            $labels[] = $d->format('d M');
            $group = $grouped->get($dateKey, collect());

            $count = $group->count();
            $total = (float) $group->sum('amount');

            $methodKey = $group->first() && isset($group->first()->payment_method) ? 'payment_method' : 'type';
            $methods = $group->countBy($methodKey)->map(function ($v, $k) {
                return $k . ' (' . $v . ')';
            })->values()->toArray();
            $methodString = empty($methods) ? '-' : implode(', ', $methods);

            $incomeData[] = $total;

            $tableRows[] = [
                'date' => $dateKey,
                'count' => $count,
                'methods' => $methodString,
                'total' => $total,
            ];
        }

        return view('dashboard.outlet_weekly', compact('outlet', 'labels', 'incomeData', 'tableRows'));
    }
}

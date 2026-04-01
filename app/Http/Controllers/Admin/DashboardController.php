<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\Invoice;
use App\Models\Issue;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // ====== Rooms ======
        $totalRooms    = Room::count();
        $emptyRooms    = Room::where('status', 'empty')->count();
        $occupiedRooms = Room::where('status', 'occupied')->count();

        // ====== Anchor month/year: lấy kỳ hóa đơn mới nhất, nếu chưa có thì dùng tháng hiện tại ======
        $latestInvoice = Invoice::select('year', 'month')
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->first();

        $anchor = $latestInvoice
            ? Carbon::createFromDate((int) $latestInvoice->year, (int) $latestInvoice->month, 1)->startOfMonth()
            : Carbon::now()->startOfMonth();

        $currentMonth = (int) $anchor->month;
        $currentYear  = (int) $anchor->year;

        // ====== Revenue of anchor month (paid) ======
        $revenue = (int) Invoice::where('month', $currentMonth)
            ->where('year', $currentYear)
            ->where('status', 'paid')
            ->sum('total');

        // ====== Issues đang xử lý (pending) ======
        // pending = sự cố đang xử lý
        $issues = Issue::where('status', 'pending')->count();

        // ====== Bills chưa thanh toán trong anchor month ======
        $bills = Invoice::where('month', $currentMonth)
            ->where('year', $currentYear)
            ->where('status', 'unpaid')
            ->count();

        // ====== Contracts sắp hết hạn (30 ngày tới) ======
        $now = Carbon::now();
        $contracts = Contract::where('status', 'active')
            ->whereNotNull('end_date')
            ->whereBetween('end_date', [
                $now->copy()->startOfDay(),
                $now->copy()->addDays(30)->endOfDay(),
            ])
            ->count();

        // ====== Chart 12 months theo anchor ======
        $start = $anchor->copy()->subMonths(11)->startOfMonth();

        $rows = Invoice::select('year', 'month', DB::raw('SUM(total) as revenue'))
            ->where('status', 'paid')
            ->where(function ($q) use ($start, $anchor) {
                $q->whereRaw("(year > ? OR (year = ? AND month >= ?))", [
                        $start->year, $start->year, $start->month
                    ])
                  ->whereRaw("(year < ? OR (year = ? AND month <= ?))", [
                        $anchor->year, $anchor->year, $anchor->month
                    ]);
            })
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $map = [];
        foreach ($rows as $r) {
            $key = sprintf('%04d-%02d', $r->year, $r->month);
            $map[$key] = (int) $r->revenue;
        }

        $chartLabels = [];
        $chartData   = [];

        for ($i = 0; $i < 12; $i++) {
            $d = $start->copy()->addMonths($i);
            $key = $d->format('Y-m');
            $chartLabels[] = $d->format('m/Y');
            $chartData[]   = $map[$key] ?? 0;
        }

        $dashboardPeriod = $anchor->format('m/Y');

        return view('admin.dashboard', compact(
            'totalRooms',
            'emptyRooms',
            'occupiedRooms',
            'revenue',
            'issues',
            'bills',
            'contracts',
            'chartLabels',
            'chartData',
            'dashboardPeriod'
        ));
    }
}
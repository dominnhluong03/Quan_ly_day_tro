@extends('admin.layout')

@section('title','Dashboard')
@section('page-title','Dashboard')

@section('content')

@php
    // Một vài chỉ số phụ cho chart (đẹp + hữu ích)
    $period = $dashboardPeriod ?? '';
    $total12 = collect($chartData ?? [])->sum();
    $avg12   = (count($chartData ?? []) > 0) ? (int) round($total12 / count($chartData)) : 0;
    $max12   = collect($chartData ?? [])->max() ?? 0;
@endphp

<div class="space-y-8">

    {{-- ====== KPI CARDS ====== --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

        <div class="bg-white/90 backdrop-blur p-6 rounded-2xl shadow-sm ring-1 ring-gray-100 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <h3 class="text-sm text-gray-500">Tổng số phòng</h3>
                <span class="text-xs px-2 py-1 rounded-full bg-gray-50 text-gray-500 ring-1 ring-gray-100">Rooms</span>
            </div>
            <p class="text-3xl font-semibold mt-3 text-gray-900">{{ $totalRooms ?? 0 }}</p>
        </div>

        <div class="bg-white/90 backdrop-blur p-6 rounded-2xl shadow-sm ring-1 ring-gray-100 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <h3 class="text-sm text-gray-500">Phòng trống</h3>
                <span class="text-xs px-2 py-1 rounded-full bg-blue-50 text-blue-600 ring-1 ring-blue-100">Empty</span>
            </div>
            <p class="text-3xl font-semibold mt-3 text-blue-700">{{ $emptyRooms ?? 0 }}</p>
        </div>

        <div class="bg-white/90 backdrop-blur p-6 rounded-2xl shadow-sm ring-1 ring-gray-100 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <h3 class="text-sm text-gray-500">Phòng đang thuê</h3>
                <span class="text-xs px-2 py-1 rounded-full bg-indigo-50 text-indigo-600 ring-1 ring-indigo-100">Occupied</span>
            </div>
            <p class="text-3xl font-semibold mt-3 text-indigo-700">{{ $occupiedRooms ?? 0 }}</p>
        </div>

        <div class="bg-white/90 backdrop-blur p-6 rounded-2xl shadow-sm ring-1 ring-gray-100 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <h3 class="text-sm text-gray-500">Doanh thu tháng</h3>
                <span class="text-xs px-2 py-1 rounded-full bg-emerald-50 text-emerald-700 ring-1 ring-emerald-100">
                    {{ $period ? $period : 'Tháng hiện tại' }}
                </span>
            </div>
            <p class="text-3xl font-semibold mt-3 text-emerald-700">{{ number_format($revenue ?? 0) }} đ</p>
            <p class="text-xs text-gray-400 mt-2">Tổng doanh thu hóa đơn <span class="font-medium">paid</span> trong kỳ</p>
        </div>

        <div class="bg-white/90 backdrop-blur p-6 rounded-2xl shadow-sm ring-1 ring-gray-100 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <h3 class="text-sm text-gray-500">Sự cố đang xử lý</h3>
                <span class="text-xs px-2 py-1 rounded-full bg-amber-50 text-amber-700 ring-1 ring-amber-100">Pending</span>
            </div>
            <p class="text-3xl font-semibold mt-3 text-amber-700">{{ $issues ?? 0 }}</p>
            <p class="text-xs text-gray-400 mt-2">Đếm theo <span class="font-medium">issue.status = pending</span></p>
        </div>

        <div class="bg-white/90 backdrop-blur p-6 rounded-2xl shadow-sm ring-1 ring-gray-100 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <h3 class="text-sm text-gray-500">Hóa đơn chưa thanh toán</h3>
                <span class="text-xs px-2 py-1 rounded-full bg-rose-50 text-rose-700 ring-1 ring-rose-100">Unpaid</span>
            </div>
            <p class="text-3xl font-semibold mt-3 text-gray-900">{{ $bills ?? 0 }}</p>
        </div>

    </div>

    {{-- ====== CHART (SANG TRỌNG HƠN) ====== --}}
    <div class="rounded-3xl overflow-hidden shadow-sm ring-1 ring-gray-100 bg-white">
        {{-- Header --}}
        <div class="p-6 sm:p-7 border-b border-gray-100">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Doanh thu 12 tháng gần nhất</h3>
                    <p class="text-sm text-gray-500 mt-1">
                        Tổng hợp từ hóa đơn <span class="font-medium">paid</span> • SUM(invoice.total) theo tháng
                    </p>
                </div>

                {{-- mini stats --}}
                <div class="flex flex-wrap gap-3">
                    <div class="px-4 py-2 rounded-2xl bg-gray-50 ring-1 ring-gray-100">
                        <div class="text-[11px] text-gray-500">Tổng 12 tháng</div>
                        <div class="text-sm font-semibold text-gray-900">{{ number_format($total12) }} đ</div>
                    </div>
                    <div class="px-4 py-2 rounded-2xl bg-gray-50 ring-1 ring-gray-100">
                        <div class="text-[11px] text-gray-500">TB / tháng</div>
                        <div class="text-sm font-semibold text-gray-900">{{ number_format($avg12) }} đ</div>
                    </div>
                    <div class="px-4 py-2 rounded-2xl bg-gray-50 ring-1 ring-gray-100">
                        <div class="text-[11px] text-gray-500">Cao nhất</div>
                        <div class="text-sm font-semibold text-gray-900">{{ number_format($max12) }} đ</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Chart area --}}
        <div class="p-6 sm:p-7">
            {{-- nền gradient nhẹ cho cảm giác premium --}}
            <div class="rounded-2xl p-4 sm:p-5 bg-gradient-to-b from-gray-50 to-white ring-1 ring-gray-100">
                <div class="relative w-full">
                    <canvas id="revenueChart" height="120"></canvas>
                </div>

                <div class="mt-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                    <div class="text-xs text-gray-500">
                        Tip: rê chuột lên điểm dữ liệu để xem chi tiết.
                    </div>
                    <div class="text-xs text-gray-400">
                        Đơn vị: VND
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const labels = @json($chartLabels ?? []);
    const dataArr = @json($chartData ?? []);

    const ctx = document.getElementById('revenueChart').getContext('2d');

    // format VND
    const fmtVND = (v) => {
        try { return new Intl.NumberFormat('vi-VN').format(v) + ' đ'; }
        catch(e) { return v + ' đ'; }
    };

    // format trục Y gọn: 1,200,000 -> 1.2M / 120,000 -> 120k
    const compact = (v) => {
        const n = Number(v) || 0;
        if (Math.abs(n) >= 1_000_000_000) return (n/1_000_000_000).toFixed(1).replace('.0','') + 'B';
        if (Math.abs(n) >= 1_000_000)     return (n/1_000_000).toFixed(1).replace('.0','') + 'M';
        if (Math.abs(n) >= 1_000)         return (n/1_000).toFixed(0) + 'k';
        return String(n);
    };

    const gradient = ctx.createLinearGradient(0, 0, 0, 260);
    gradient.addColorStop(0, 'rgba(59, 130, 246, 0.18)');  // blue-ish
    gradient.addColorStop(1, 'rgba(59, 130, 246, 0.00)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels,
            datasets: [{
                label: 'Doanh thu',
                data: dataArr,
                fill: true,
                backgroundColor: gradient,
                borderColor: 'rgba(59, 130, 246, 0.9)',
                borderWidth: 2,
                tension: 0.35,
                pointRadius: 3,
                pointHoverRadius: 6,
                pointBackgroundColor: 'rgba(59, 130, 246, 1)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: {
                    display: true,
                    labels: {
                        usePointStyle: true,
                        pointStyle: 'circle',
                        boxWidth: 10,
                        boxHeight: 10
                    }
                },
                tooltip: {
                    padding: 12,
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return ' ' + fmtVND(context.parsed.y);
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { color: '#6B7280' } // gray-500
                },
                y: {
                    grid: { color: 'rgba(17, 24, 39, 0.06)' }, // gray-900 nhẹ
                    ticks: {
                        color: '#6B7280',
                        callback: function(value) { return compact(value); }
                    }
                }
            }
        }
    });
</script>

@endsection
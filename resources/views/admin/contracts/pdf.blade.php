<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>Hợp đồng #{{ $contract->id }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 13px; color:#111; }
        .title { font-size: 18px; font-weight: bold; text-align: center; margin: 6px 0 18px; }
        .muted { color:#666; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        td, th { border: 1px solid #ddd; padding: 8px; vertical-align: top; }
        .no-border td { border: none; padding: 4px 0; }
        .section { margin-top: 12px; font-weight: bold; }
    </style>
</head>
<body>

@php
    $room = $contract->room;
    $maxPeople = $room?->max_people ?? 1;

    // danh sách người đang ở (hợp đồng active trong phòng)
    $activeContractsInRoom = $room?->contracts?->where('status','active') ?? collect();
    $activeNames = $activeContractsInRoom
        ->map(fn($ct) => $ct->tenant?->user?->name)
        ->filter()
        ->unique()
        ->values();

    $activeCount = $activeNames->count();
@endphp

<div class="title">HỢP ĐỒNG THUÊ PHÒNG</div>

<table class="no-border">
    <tr>
        <td><b>Mã hợp đồng:</b> #{{ $contract->id }}</td>
        <td style="text-align:right" class="muted">
            <b>Ngày tạo:</b> {{ \Carbon\Carbon::parse($contract->created_at)->format('d/m/Y') }}
        </td>
    </tr>
</table>

<table>
    <tr>
        <th style="width:50%">Thông tin bên thuê</th>
        <th style="width:50%">Thông tin phòng</th>
    </tr>
    <tr>
        <td>
            <b>Khách thuê:</b> {{ $contract->tenant?->user?->name ?? 'N/A' }}<br>
            <b>Email:</b> {{ $contract->tenant?->user?->email ?? '---' }}<br>
            <b>SĐT:</b> {{ $contract->tenant?->phone ?? '---' }}
        </td>
        <td>
            <b>Phòng:</b> {{ $room?->room_code ?? '---' }}<br>
            <b>Sức chứa:</b> {{ $activeCount }}/{{ $maxPeople }} người<br>
            <b>Đang ở:</b> {{ $activeNames->isNotEmpty() ? $activeNames->implode(', ') : '---' }}<br>
            <b>Giá phòng/tháng:</b> {{ number_format($room?->price ?? 0) }} đ
        </td>
    </tr>
</table>

<div class="section">1) Thời hạn hợp đồng</div>
<table>
    <tr>
        <td><b>Ngày bắt đầu:</b> {{ \Carbon\Carbon::parse($contract->start_date)->format('d/m/Y') }}</td>
        <td><b>Ngày kết thúc:</b> {{ $contract->end_date ? \Carbon\Carbon::parse($contract->end_date)->format('d/m/Y') : '---' }}</td>
    </tr>
    <tr>
        <td><b>Tiền đặt cọc:</b> {{ number_format($contract->deposit ?? 0) }} đ</td>
        <td><b>Trạng thái:</b> {{ strtoupper($contract->status) }}</td>
    </tr>
</table>

<div class="section">2) Đơn giá điện / nước</div>
<table>
    <tr>
        <td><b>Giá điện:</b> {{ number_format($contract->electric_price ?? 0) }} đ/kWh</td>
        <td><b>Giá nước:</b> {{ number_format($contract->water_price ?? 0) }} đ/m³</td>
    </tr>
    <tr>
        <td colspan="2">
            <b>Dịch vụ khác:</b> {{ $contract->service_note ? $contract->service_note : '---' }}
        </td>
    </tr>
</table>

<div class="section">3) Điều khoản chung</div>
<p class="muted">
    - Hai bên cam kết thực hiện đúng các thỏa thuận trong hợp đồng.<br>
    - Thanh toán đúng hạn theo kỳ, giữ gìn tài sản và vệ sinh phòng ở.<br>
    - Mọi phát sinh được ưu tiên thương lượng; nếu không thành sẽ giải quyết theo quy định pháp luật.
</p>

<br><br>
<table class="no-border">
    <tr>
        <td style="text-align:center; width:50%">
            <b>BÊN CHO THUÊ</b><br><br><br>
            (Ký & ghi rõ họ tên)
        </td>
        <td style="text-align:center; width:50%">
            <b>BÊN THUÊ</b><br><br><br>
            (Ký & ghi rõ họ tên)
        </td>
    </tr>
</table>

</body>
</html>
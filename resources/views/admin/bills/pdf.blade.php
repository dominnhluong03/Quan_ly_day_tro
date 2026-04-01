<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #111; }
        .box { border: 1px solid #ddd; padding: 12px; border-radius: 10px; }
        .title { font-size: 18px; font-weight: 700; margin-bottom: 6px; }
        .muted { color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border-bottom: 1px solid #eee; padding: 8px 6px; }
        th { background: #f6f7fb; text-align: left; }
        .right { text-align: right; }
        .total { font-size: 14px; font-weight: 700; }
        .row { display:flex; justify-content:space-between; gap: 12px; }
        .col { width: 48%; }
        .qr { margin-top: 10px; text-align:center; }
        .qr img { width: 160px; height: 160px; }
    </style>
</head>
<body>

<div class="box">
    <div class="title">HÓA ĐƠN PHÒNG TRỌ</div>
    <div class="muted">
        Mã hóa đơn: #{{ $invoice->id }} |
        Kỳ: {{ str_pad($invoice->month,2,'0',STR_PAD_LEFT) }}/{{ $invoice->year }}
    </div>

    <div style="height:10px"></div>

    <div class="row">
        <div class="col">
            <b>Phòng:</b> {{ $invoice->contract?->room?->room_code }} <br>
            <b>Hợp đồng:</b> #{{ $invoice->contract_id }} <br>
            <b>Trạng thái:</b> {{ $invoice->status == 'paid' ? 'Đã thanh toán' : 'Chưa thanh toán' }}
        </div>
        <div class="col">
            <b>Ngày tạo:</b> {{ \Carbon\Carbon::parse($invoice->created_at)->format('d/m/Y') }} <br>
            <b>Điện:</b> {{ $invoice->meter?->electric_old }} → {{ $invoice->meter?->electric_new }} <br>
            <b>Nước:</b> {{ $invoice->meter?->water_old }} → {{ $invoice->meter?->water_new }}
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Hạng mục</th>
                <th class="right">Số tiền</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Tiền phòng</td>
                <td class="right">{{ number_format($invoice->room_price ?? 0) }} đ</td>
            </tr>
            <tr>
                <td>Tiền điện</td>
                <td class="right">{{ number_format($invoice->electric_cost ?? 0) }} đ</td>
            </tr>
            <tr>
                <td>Tiền nước</td>
                <td class="right">{{ number_format($invoice->water_cost ?? 0) }} đ</td>
            </tr>

            @if($invoice->services && $invoice->services->count())
                @foreach($invoice->services as $s)
                    <tr>
                        <td>Dịch vụ: {{ $s->service_name }}</td>
                        <td class="right">{{ number_format($s->price ?? 0) }} đ</td>
                    </tr>
                @endforeach
            @endif

            <tr>
                <td class="total">TỔNG CỘNG</td>
                <td class="right total">{{ number_format($invoice->total ?? 0) }} đ</td>
            </tr>
        </tbody>
    </table>

    <div class="qr">
        <div class="muted" style="margin-bottom:6px;">Quét mã để thanh toán</div>

        @php
            // Ưu tiên qr_image lưu trong DB, nếu không có thì dùng mặc định
            $qr = $invoice->qr_image ?? 'storage/qr/owner_qr.jpg';
            $qrPath = public_path($qr); // dompdf cần đường dẫn tuyệt đối
        @endphp

        @if(file_exists($qrPath))
            <img src="{{ $qrPath }}" alt="QR">
        @else
            <div class="muted">Không tìm thấy QR: {{ $qr }}</div>
        @endif

        <div class="muted" style="margin-top:6px;">
            Nội dung CK: <b>HD{{ $invoice->id }}_{{ str_pad($invoice->month,2,'0',STR_PAD_LEFT) }}/{{ $invoice->year }}</b>
        </div>
    </div>

</div>

</body>
</html>
@extends('tenant.layout')

@section('title','Sự cố')
@section('page_title','Sự cố')

@section('content')
<div class="max-w-5xl mx-auto">

    @if(session('success'))
        <div class="mb-6 px-5 py-4 rounded-xl bg-emerald-50 text-emerald-700 font-bold">
            ✓ {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 px-5 py-4 rounded-xl bg-rose-50 text-rose-700 font-bold">
            ❌ {{ $errors->first() }}
        </div>
    @endif

    <div class="bg-white rounded-2xl border overflow-hidden mb-6">
        <div class="px-6 py-5 border-b">
            <h2 class="text-xl font-black text-slate-800">Đồ dùng trong phòng</h2>
            <p class="text-sm text-slate-500">Chọn đồ bị hỏng và gửi cho chủ trọ</p>
        </div>

        @if(!$roomId)
            <div class="px-6 py-10 text-slate-400 font-bold text-center">
                Bạn chưa có hợp đồng active nên chưa có phòng để báo sự cố.
            </div>
        @else
            <div class="p-6">
                <form method="POST" action="{{ route('tenant.issues.store') }}" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @csrf

                    <div>
                        <label class="text-xs font-bold text-slate-600">Chọn đồ dùng bị hỏng</label>
                        <select name="room_asset_id" required class="w-full mt-1 p-3 bg-slate-50 rounded-xl font-bold">
                            <option value="">-- Chọn --</option>
                            @foreach($roomAssets as $a)
                                <option value="{{ $a->id }}">{{ $a->name }} (SL: {{ $a->quantity }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-600">Tiêu đề (tuỳ chọn)</label>
                        <input name="title" class="w-full mt-1 p-3 bg-slate-50 rounded-xl font-bold" placeholder="VD: Điều hoà không lạnh">
                    </div>

                    <div class="md:col-span-2">
                        <label class="text-xs font-bold text-slate-600">Mô tả</label>
                        <textarea name="content" rows="3" class="w-full mt-1 p-3 bg-slate-50 rounded-xl font-bold" placeholder="Mô tả tình trạng, thời điểm xảy ra..."></textarea>
                    </div>

                    <div class="md:col-span-2">
                        <label class="text-xs font-bold text-slate-600">Ảnh minh chứng (tuỳ chọn)</label>
                        <input type="file" name="image" accept="image/*" class="w-full mt-1 p-3 bg-slate-50 rounded-xl font-bold">
                    </div>

                    <div class="md:col-span-2 flex justify-end">
                        <button class="px-6 py-3 bg-slate-900 text-white rounded-xl font-black hover:bg-indigo-600 transition">
                            Gửi báo sự cố
                        </button>
                    </div>
                </form>
            </div>
        @endif
    </div>

    <div class="bg-white rounded-2xl border overflow-hidden">
        <div class="px-6 py-5 border-b">
            <h2 class="text-xl font-black text-slate-800">Lịch sử báo sự cố</h2>
            <p class="text-sm text-slate-500">Theo dõi trạng thái xử lý</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 text-xs uppercase text-slate-500">
                    <tr>
                        <th class="px-6 py-4 text-left">Đồ dùng</th>
                        <th class="px-6 py-4 text-left">Nội dung</th>
                        <th class="px-6 py-4 text-center">Trạng thái</th>
                        <th class="px-6 py-4 text-center">Ảnh</th>
                        <th class="px-6 py-4 text-right">Thời gian</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($issues as $i)
                        <tr class="border-t">
                            <td class="px-6 py-4 font-black">{{ $i->asset?->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm">
                                <div class="font-bold">{{ $i->title }}</div>
                                <div class="text-slate-500">{{ $i->content }}</div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($i->status === 'pending')
                                    <span class="px-3 py-1 rounded-full bg-amber-50 text-amber-700 text-xs font-black">CHỜ XỬ LÝ</span>
                                @elseif($i->status === 'fixing')
                                    <span class="px-3 py-1 rounded-full bg-indigo-50 text-indigo-700 text-xs font-black">ĐANG SỬA</span>
                                @else
                                    <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-black">ĐÃ SỬA XONG</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($i->image_path)
                                    <a class="px-3 py-2 rounded-lg bg-slate-900 text-white text-sm font-bold"
                                       target="_blank"
                                       href="{{ asset('storage/'.$i->image_path) }}">Xem</a>
                                @else
                                    <span class="text-slate-400 text-sm">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right text-sm text-slate-500">
                                {{ $i->created_at?->format('d/m/Y H:i') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-slate-400">
                                Chưa có sự cố
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>
@endsection
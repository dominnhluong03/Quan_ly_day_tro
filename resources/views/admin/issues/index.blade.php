@extends('admin.layout')

@section('title','Sự cố')
@section('page_title','Sự cố')

@section('content')
<div class="max-w-[1400px] mx-auto">

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

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-black text-slate-800">Quản lý sự cố</h2>
            <p class="text-slate-500 text-sm">Tenant báo đồ hỏng trong phòng</p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.issues.index', ['tab'=>'pending']) }}"
               class="px-4 py-2 rounded-xl font-bold text-sm {{ $tab==='pending' ? 'bg-slate-900 text-white' : 'bg-white border text-slate-600' }}">
                Chờ xử lý
            </a>
            <a href="{{ route('admin.issues.index', ['tab'=>'fixing']) }}"
               class="px-4 py-2 rounded-xl font-bold text-sm {{ $tab==='fixing' ? 'bg-slate-900 text-white' : 'bg-white border text-slate-600' }}">
                Đang sửa
            </a>
            <a href="{{ route('admin.issues.index', ['tab'=>'resolved']) }}"
               class="px-4 py-2 rounded-xl font-bold text-sm {{ $tab==='resolved' ? 'bg-slate-900 text-white' : 'bg-white border text-slate-600' }}">
                Đã sửa
            </a>
        </div>
    </div>

    <div class="bg-white rounded-2xl border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 text-xs uppercase text-slate-500">
                    <tr>
                        <th class="px-6 py-4 text-left">Phòng</th>
                        <th class="px-6 py-4 text-left">Khách</th>
                        <th class="px-6 py-4 text-left">Đồ hỏng</th>
                        <th class="px-6 py-4 text-left">Nội dung</th>
                        <th class="px-6 py-4 text-center">Ảnh</th>
                        <th class="px-6 py-4 text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($issues as $i)
                        <tr class="border-t hover:bg-slate-50">
                            <td class="px-6 py-4 font-black">{{ $i->room?->room_code ?? ('#'.$i->room_id) }}</td>
                            <td class="px-6 py-4">{{ $i->tenant?->user?->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 font-bold">{{ $i->asset?->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm">
                                <div class="font-bold">{{ $i->title }}</div>
                                <div class="text-slate-500">{{ $i->content }}</div>
                                <div class="text-[11px] text-slate-400 mt-1">{{ $i->created_at?->format('d/m/Y H:i') }}</div>
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
                            <td class="px-6 py-4 text-right">
                                <div class="inline-flex gap-2">
                                    @if($i->status !== 'fixing' && $i->status !== 'resolved')
                                        <form method="POST" action="{{ route('admin.issues.fixing', $i->id) }}">
                                            @csrf
                                            <button class="px-4 py-2 rounded-xl bg-indigo-600 text-white font-bold text-sm">
                                                Chờ sửa
                                            </button>
                                        </form>
                                    @endif

                                    @if($i->status !== 'resolved')
                                        <form method="POST" action="{{ route('admin.issues.resolved', $i->id) }}">
                                            @csrf
                                            <button class="px-4 py-2 rounded-xl bg-emerald-600 text-white font-bold text-sm">
                                                Sửa xong
                                            </button>
                                        </form>
                                    @else
                                        <span class="px-4 py-2 rounded-xl bg-slate-100 text-slate-600 font-bold text-sm">
                                            ✅ Đã xong
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-slate-400">
                                Không có dữ liệu
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
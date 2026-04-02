@extends('admin.layout')

@section('title','Quản lý hợp đồng')
@section('page_title','Hợp đồng thuê phòng')

@section('content')
<style>
    @keyframes modalSpring {
        0% { opacity: 0; transform: scale(0.95) translateY(10px); }
        100% { opacity: 1; transform: scale(1) translateY(0); }
    }

    .animate-modal { animation: modalSpring 0.3s ease-out; }

    .focus-ring:focus {
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        border-color: #6366f1;
    }

    .custom-table-scroll::-webkit-scrollbar { height: 6px; }
    .custom-table-scroll::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }

    .modal-scroll::-webkit-scrollbar { width: 5px; }
    .modal-scroll::-webkit-scrollbar-track { background: #f1f5f9; }
    .modal-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .animate-fadeIn { animation: fadeIn 0.45s ease-out; }
</style>

<div class="max-w-[1400px] mx-auto">
    @if(session('success'))
        <div class="mb-6 px-5 py-4 rounded-xl bg-emerald-50 text-emerald-700 font-bold border border-emerald-100 animate-fadeIn">
            ✓ {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 px-5 py-4 rounded-xl bg-rose-50 text-rose-700 font-bold border border-rose-100 animate-fadeIn">
            ❌ {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 px-5 py-4 rounded-xl bg-rose-50 text-rose-700 font-bold border border-rose-100 animate-fadeIn">
            ❌ {{ $errors->first() }}
        </div>
    @endif

    <div class="flex justify-between items-center mb-8 gap-3">
        <div>
            <h2 class="text-2xl font-black text-slate-900">Quản lý hợp đồng</h2>
            <p class="text-slate-500 text-sm">Pháp lý và thời hạn thuê phòng</p>
        </div>

        <div class="flex items-center gap-3">
            <div class="relative hidden lg:block">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">🔍</span>
                <input type="text" id="tableSearch" placeholder="Tìm tên khách, mã phòng..."
                       class="pl-11 pr-4 py-2.5 w-64 bg-white border rounded-xl text-sm focus:ring-2 focus:ring-indigo-500/20 outline-none transition-all focus-ring">
            </div>
            <button type="button" onclick="openModal()"
                    class="px-6 py-3 bg-slate-900 text-white rounded-xl font-bold hover:bg-indigo-600 transition shadow-lg shadow-slate-200">
                ➕ Thêm hợp đồng
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-white p-6 rounded-xl border text-center">
            <p class="text-slate-400 text-xs font-bold uppercase tracking-wider">Tổng hợp đồng</p>
            <h3 class="text-3xl font-black text-slate-800">{{ $contracts->count() }}</h3>
        </div>

        <div class="bg-indigo-600 p-6 rounded-xl text-white text-center shadow-lg shadow-indigo-100">
            <p class="text-xs font-bold uppercase tracking-wider opacity-80">Đang hiệu lực</p>
            <h3 class="text-3xl font-black">{{ $contracts->where('status','active')->count() }}</h3>
        </div>
    </div>

    <div class="bg-white rounded-xl border overflow-hidden shadow-sm">
        <div class="overflow-x-auto custom-table-scroll">
            <table class="w-full">
                <thead class="bg-slate-100 text-xs uppercase text-slate-500">
                    <tr>
                        <th class="px-6 py-4 text-left">Khách & Phòng</th>
                        <th class="px-6 py-4 text-left">Thời hạn</th>
                        <th class="px-6 py-4 text-center">Đặt cọc</th>
                        <th class="px-6 py-4 text-center">Trạng thái</th>
                        <th class="px-6 py-4 text-center">Tệp tin</th>
                        <th class="px-6 py-4 text-right">Thao tác</th>
                    </tr>
                </thead>

                <tbody class="divide-y">
                    @forelse($contracts as $c)
                        @php
                            $isLiquidation = in_array($c->status, ['expired', 'cancelled']);
                            $viewRoute = null;
                            $downloadFile = null;
                            $fileLabel = null;

                            if ($isLiquidation && !empty($c->liquidation_file)) {
                                $viewRoute = route('admin.contracts.viewLiquidation', $c->id);
                                $downloadFile = asset($c->liquidation_file);
                                $fileLabel = 'PDF thanh lý';
                            } elseif (!empty($c->contract_file)) {
                                $viewRoute = route('admin.contracts.view', $c->id);
                                $downloadFile = asset($c->contract_file);
                                $fileLabel = 'PDF hợp đồng';
                            }
                        @endphp

                        <tr class="hover:bg-slate-50 transition-colors search-item">
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <b class="text-slate-700 tenant-name">{{ $c->tenant?->user?->name ?? 'N/A' }}</b>
                                    <span class="text-[11px] font-bold text-indigo-500 uppercase room-code">
                                        Phòng: {{ $c->room?->room_code ?? 'N/A' }}
                                    </span>
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                <div class="text-xs font-bold text-slate-600">
                                    <span>{{ \Carbon\Carbon::parse($c->start_date)->format('d/m/Y') }}</span>
                                    <span class="mx-1 text-slate-300">→</span>
                                    <span>{{ $c->end_date ? \Carbon\Carbon::parse($c->end_date)->format('d/m/Y') : '---' }}</span>
                                </div>
                            </td>

                            <td class="px-6 py-4 text-center">
                                <span class="text-sm font-black text-slate-800">
                                    {{ number_format($c->deposit ?? 0) }}
                                    <small class="ml-0.5 text-[10px] text-slate-400">đ</small>
                                </span>
                            </td>

                            <td class="px-6 py-4 text-center">
                                @if($c->status === 'active')
                                    <span class="font-bold text-xs text-emerald-600">Hiệu lực</span>
                                @elseif($c->status === 'expired')
                                    <span class="font-bold text-xs text-slate-500">Hết hạn</span>
                                @else
                                    <span class="font-bold text-xs text-rose-600">Hủy</span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-center">
                                @if($viewRoute && $downloadFile)
                                    <div class="flex flex-col items-center gap-2">
                                        <span class="text-[10px] font-bold uppercase {{ $isLiquidation ? 'text-orange-500' : 'text-indigo-500' }}">
                                            {{ $fileLabel }}
                                        </span>

                                        <div class="flex justify-center gap-2">
                                            <a href="{{ $viewRoute }}" target="_blank"
                                               class="px-3 py-2 rounded-lg {{ $isLiquidation ? 'bg-orange-50 text-orange-700 hover:bg-orange-100' : 'bg-indigo-50 text-indigo-700 hover:bg-indigo-100' }} font-bold text-sm transition">
                                                📄 Xem
                                            </a>

                                            <a href="{{ $downloadFile }}" download
                                               class="px-3 py-2 rounded-lg bg-emerald-50 text-emerald-700 font-bold text-sm hover:bg-emerald-100 transition">
                                                📥 Tải
                                            </a>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-[10px] font-bold text-slate-300 uppercase">Chưa có</span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.contracts.edit', $c->id) }}"
                                       class="px-3 py-2 rounded-lg bg-indigo-50 text-indigo-700 font-bold text-sm hover:bg-indigo-100 transition">
                                        ✏️ Sửa
                                    </a>

                                    <form method="POST" action="{{ route('admin.contracts.destroy', $c->id) }}"
                                          onsubmit="return confirm('Xác nhận xóa hợp đồng này?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="px-3 py-2 rounded-lg bg-rose-50 text-rose-600 font-bold text-sm hover:bg-rose-100 transition">
                                            🗑 Xóa
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-10 text-center text-slate-400">Chưa có dữ liệu hợp đồng</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL THÊM HỢP ĐỒNG --}}
<div id="modal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-[300] p-4">
    <div class="bg-white w-full max-w-2xl rounded-xl animate-modal overflow-hidden max-h-[95vh] flex flex-col shadow-2xl">
        <div class="p-6 border-b flex items-center justify-between bg-white sticky top-0 z-10">
            <div>
                <h3 class="text-xl font-black text-slate-900">Tạo hợp đồng mới</h3>
                <p class="text-slate-400 text-xs font-bold uppercase tracking-tight">Thiết lập điều khoản thuê phòng</p>
            </div>
            <button type="button" onclick="closeModal()" class="text-slate-400 hover:text-rose-500 font-black text-xl transition-colors">✕</button>
        </div>

        <div class="p-6 overflow-y-auto modal-scroll">
            <form method="POST" action="{{ route('admin.contracts.store') }}" class="space-y-5">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-bold text-slate-500 mb-1 block">Khách thuê</label>
                        <select name="tenant_id" required
                                class="w-full p-3 bg-slate-50 rounded-xl font-bold text-slate-700 border border-transparent focus:border-indigo-500 outline-none">
                            <option value="">-- Chọn khách --</option>
                            @foreach($tenants as $t)
                                <option value="{{ $t->id }}">{{ $t->user?->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-500 mb-1 block">Phòng thuê</label>
                        <select name="room_id" id="roomSelect" required
                                class="w-full p-3 bg-slate-50 rounded-xl font-bold text-slate-700 border border-transparent focus:border-indigo-500 outline-none">
                            <option value="">-- Chọn phòng --</option>
                            @foreach($rooms as $r)
                                @php
                                    $activeContracts = $r->contracts->where('status', 'active')->map(function($contract) {
                                        return [
                                            'id' => $contract->id,
                                            'tenant_name' => $contract->tenant?->user?->name ?? 'N/A',
                                            'start_date' => $contract->start_date ? \Carbon\Carbon::parse($contract->start_date)->format('d/m/Y') : '---',
                                            'end_date' => $contract->end_date ? \Carbon\Carbon::parse($contract->end_date)->format('d/m/Y') : '---',
                                            'deposit' => number_format($contract->deposit ?? 0) . ' đ',
                                            'electric_price' => number_format($contract->electric_price ?? 0) . ' đ',
                                            'water_price' => number_format($contract->water_price ?? 0) . ' đ',
                                            'service_note' => $contract->service_note ?? '',
                                        ];
                                    })->values();
                                @endphp
                                <option value="{{ $r->id }}"
                                    data-room-code="{{ $r->room_code }}"
                                    data-max-people="{{ $r->max_people ?? 1 }}"
                                    data-active-count="{{ $r->active_count ?? 0 }}"
                                    data-contracts='@json($activeContracts)'>
                                    {{ $r->room_code }} ({{ $r->active_count }}/{{ $r->max_people ?? 1 }} người)
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div id="roomContractPreview" class="hidden p-4 bg-slate-50 rounded-xl border border-slate-200">
                    <div class="flex items-center justify-between mb-3">
                        <p class="font-black text-sm text-slate-700">📋 Hợp đồng active hiện tại của phòng</p>
                        <span id="roomCapacityText" class="text-xs font-bold text-indigo-600"></span>
                    </div>

                    <div id="roomContractList" class="space-y-3"></div>

                    <div id="roomNoContract" class="hidden text-sm font-bold text-slate-400">
                        Phòng này hiện chưa có hợp đồng active nào.
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-bold text-slate-500 mb-1 block">Ngày bắt đầu</label>
                        <input type="date" name="start_date" required
                               class="w-full p-3 bg-slate-50 rounded-xl font-bold text-slate-700 border border-transparent focus:border-indigo-500 outline-none">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-500 mb-1 block">Ngày kết thúc</label>
                        <input type="date" name="end_date"
                               class="w-full p-3 bg-slate-50 rounded-xl font-bold text-slate-700 border border-transparent focus:border-indigo-500 outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-bold text-slate-500 mb-1 block">Giá điện (VNĐ)</label>
                        <input type="number" name="electric_price" required placeholder="4000"
                               class="w-full p-3 bg-slate-50 rounded-xl font-bold text-slate-700 border border-transparent focus:border-indigo-500 outline-none">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-500 mb-1 block">Giá nước (VNĐ)</label>
                        <input type="number" name="water_price" placeholder="30000"
                               class="w-full p-3 bg-slate-50 rounded-xl font-bold text-slate-700 border border-transparent focus:border-indigo-500 outline-none">
                    </div>
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-500 mb-1 block">Tiền đặt cọc (VNĐ)</label>
                    <input type="number" name="deposit" required placeholder="5000000"
                           class="w-full p-3 bg-slate-100 rounded-xl font-black text-indigo-700 text-lg border border-transparent focus:border-indigo-500 outline-none">
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-500 mb-1 block">Ghi chú dịch vụ</label>
                    <textarea name="service_note" rows="2" placeholder="Wifi, rác, xe..."
                              class="w-full p-3 bg-slate-50 rounded-xl font-bold text-slate-700 border border-transparent focus:border-indigo-500 outline-none"></textarea>
                </div>

                <div class="flex gap-3 pt-6 border-t border-slate-100 sticky bottom-0 bg-white">
                    <button type="submit"
                            class="flex-1 bg-slate-900 text-white py-3.5 rounded-xl font-black uppercase tracking-widest text-sm hover:bg-indigo-600 transition-all shadow-lg shadow-indigo-100">
                        Lưu & Xuất PDF
                    </button>
                    <button type="button" onclick="closeModal()" class="px-8 text-slate-400 font-bold hover:text-slate-600">
                        Đóng
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('tableSearch');
        const rows = document.querySelectorAll('.search-item');

        if (searchInput) {
            searchInput.addEventListener('input', function(e) {
                const query = e.target.value.toLowerCase().trim();
                rows.forEach(row => {
                    const tenant = row.querySelector('.tenant-name')?.textContent.toLowerCase() || '';
                    const room = row.querySelector('.room-code')?.textContent.toLowerCase() || '';
                    row.style.display = (tenant.includes(query) || room.includes(query)) ? '' : 'none';
                });
            });
        }

        const roomSelect = document.getElementById('roomSelect');
        const preview = document.getElementById('roomContractPreview');
        const list = document.getElementById('roomContractList');
        const noContract = document.getElementById('roomNoContract');
        const capacityText = document.getElementById('roomCapacityText');

        if (roomSelect && preview && list && noContract && capacityText) {
            roomSelect.addEventListener('change', function() {
                const selected = roomSelect.options[roomSelect.selectedIndex];

                if (!selected || !selected.value) {
                    preview.classList.add('hidden');
                    list.innerHTML = '';
                    noContract.classList.add('hidden');
                    capacityText.textContent = '';
                    return;
                }

                const roomCode = selected.dataset.roomCode || '';
                const maxPeople = selected.dataset.maxPeople || '1';
                const activeCount = selected.dataset.activeCount || '0';
                const contracts = JSON.parse(selected.dataset.contracts || '[]');

                preview.classList.remove('hidden');
                capacityText.textContent = `Phòng ${roomCode}: ${activeCount}/${maxPeople} người`;

                list.innerHTML = '';

                if (!contracts.length) {
                    noContract.classList.remove('hidden');
                    return;
                }

                noContract.classList.add('hidden');

                contracts.forEach((contract) => {
                    const item = document.createElement('div');
                    item.className = 'p-4 bg-white rounded-xl border border-slate-200';

                    item.innerHTML = `
                        <div class="flex items-center justify-between mb-2">
                            <p class="font-black text-slate-800">Hợp đồng #${contract.id}</p>
                            <span class="text-[10px] px-2 py-1 rounded-full bg-emerald-50 text-emerald-600 font-black uppercase">Active</span>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                            <div><span class="text-slate-400 font-bold">Khách thuê:</span><div class="font-bold text-slate-700">${contract.tenant_name}</div></div>
                            <div><span class="text-slate-400 font-bold">Tiền cọc:</span><div class="font-bold text-slate-700">${contract.deposit}</div></div>
                            <div><span class="text-slate-400 font-bold">Bắt đầu:</span><div class="font-bold text-slate-700">${contract.start_date}</div></div>
                            <div><span class="text-slate-400 font-bold">Kết thúc:</span><div class="font-bold text-slate-700">${contract.end_date}</div></div>
                            <div><span class="text-slate-400 font-bold">Giá điện:</span><div class="font-bold text-slate-700">${contract.electric_price}</div></div>
                            <div><span class="text-slate-400 font-bold">Giá nước:</span><div class="font-bold text-slate-700">${contract.water_price}</div></div>
                        </div>
                        ${contract.service_note ? `<div class="mt-3 text-xs text-slate-500"><span class="font-bold">Ghi chú:</span> ${contract.service_note}</div>` : ''}
                    `;

                    list.appendChild(item);
                });
            });
        }
    });

    function openModal() {
        const m = document.getElementById('modal');
        if (!m) return;
        m.classList.remove('hidden');
        m.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        const m = document.getElementById('modal');
        if (!m) return;
        m.classList.add('hidden');
        m.classList.remove('flex');
        document.body.style.overflow = 'auto';
    }

    window.onclick = function(e) {
        if (e.target && e.target.id === 'modal') closeModal();
    };

    @if($errors->any())
        openModal();
    @endif
</script>
@endsection
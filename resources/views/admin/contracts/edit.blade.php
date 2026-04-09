@extends('admin.layout')

@section('title', 'Chỉnh sửa hợp đồng')
@section('page_title', 'Chỉnh sửa hợp đồng thuê phòng')

@section('content')
<style>
    .focus-ring:focus {
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        border-color: #6366f1;
    }

    .custom-table-scroll::-webkit-scrollbar { height: 6px; }
    .custom-table-scroll::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }

    .animate-fadeIn {
        animation: fadeIn 0.45s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<div class="max-w-4xl mx-auto">
    <div class="mb-8 flex items-center justify-between gap-4">
        <div>
            <a href="{{ route('admin.contracts.index') }}"
               class="inline-flex items-center gap-2 text-slate-400 hover:text-indigo-600 font-bold text-sm transition mb-3">
                ← Quay lại
            </a>

            <h2 class="text-3xl font-black text-slate-900">Chỉnh sửa hợp đồng</h2>
            <p class="text-slate-500 text-sm">
                Cập nhật thông tin hợp đồng thuê phòng
            </p>
        </div>
    </div>

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

    <div class="bg-white rounded-2xl border shadow-sm overflow-hidden">
        <div class="p-8">
            <form method="POST" action="{{ route('admin.contracts.update', $contract->id) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="text-xs font-bold text-slate-500 mb-1 block">Khách thuê</label>
                        <select name="tenant_id" required
                                class="w-full p-3 bg-slate-50 rounded-xl font-bold text-slate-700 border border-transparent focus:border-indigo-500 outline-none focus-ring">
                            <option value="">-- Chọn khách --</option>
                            @foreach($tenants as $t)
                                <option value="{{ $t->id }}"
                                    {{ old('tenant_id', $contract->tenant_id) == $t->id ? 'selected' : '' }}>
                                    {{ $t->user?->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('tenant_id')
                            <p class="mt-2 text-sm font-bold text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-500 mb-1 block">Phòng thuê</label>
                        <select name="room_id" id="roomSelect" required
                                class="w-full p-3 bg-slate-50 rounded-xl font-bold text-slate-700 border border-transparent focus:border-indigo-500 outline-none focus-ring">
                            <option value="">-- Chọn phòng --</option>
                            @foreach($rooms as $r)
                                @php
                                    $activeContracts = $r->contracts
                                        ->where('status', 'active')
                                        ->where('id', '!=', $contract->id)
                                        ->map(function($item) {
                                            return [
                                                'id' => $item->id,
                                                'tenant_name' => $item->tenant?->user?->name ?? 'N/A',
                                                'start_date' => $item->start_date ? \Carbon\Carbon::parse($item->start_date)->format('d/m/Y') : '---',
                                                'end_date' => $item->end_date ? \Carbon\Carbon::parse($item->end_date)->format('d/m/Y') : '---',
                                                'deposit' => number_format($item->deposit ?? 0) . ' đ',
                                                'electric_price' => number_format($item->electric_price ?? 0) . ' đ',
                                                'water_price' => number_format($item->water_price ?? 0) . ' đ',
                                                'service_note' => $item->service_note ?? '',
                                            ];
                                        })->values();
                                @endphp

                                <option value="{{ $r->id }}"
                                    data-room-code="{{ $r->room_code }}"
                                    data-max-people="{{ $r->max_people ?? 1 }}"
                                    data-active-count="{{ $r->active_count ?? 0 }}"
                                    data-contracts='@json($activeContracts)'
                                    {{ old('room_id', $contract->room_id) == $r->id ? 'selected' : '' }}>
                                    {{ $r->room_code }} ({{ $r->active_count }}/{{ $r->max_people ?? 1 }} người)
                                </option>
                            @endforeach
                        </select>
                        @error('room_id')
                            <p class="mt-2 text-sm font-bold text-rose-500">{{ $message }}</p>
                        @enderror
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

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="text-xs font-bold text-slate-500 mb-1 block">Ngày bắt đầu</label>
                        <input type="date"
                               name="start_date"
                               required
                               value="{{ old('start_date', $contract->start_date ? \Carbon\Carbon::parse($contract->start_date)->format('Y-m-d') : '') }}"
                               class="w-full p-3 bg-slate-50 rounded-xl font-bold text-slate-700 border border-transparent focus:border-indigo-500 outline-none focus-ring">
                        @error('start_date')
                            <p class="mt-2 text-sm font-bold text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-500 mb-1 block">Ngày kết thúc</label>
                        <input type="date"
                               name="end_date"
                               value="{{ old('end_date', $contract->end_date ? \Carbon\Carbon::parse($contract->end_date)->format('Y-m-d') : '') }}"
                               class="w-full p-3 bg-slate-50 rounded-xl font-bold text-slate-700 border border-transparent focus:border-indigo-500 outline-none focus-ring">
                        @error('end_date')
                            <p class="mt-2 text-sm font-bold text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="text-xs font-bold text-slate-500 mb-1 block">Giá điện (VNĐ)</label>
                        <input type="number"
                               name="electric_price"
                               required
                               placeholder="4000"
                               value="{{ old('electric_price', $contract->electric_price) }}"
                               class="w-full p-3 bg-slate-50 rounded-xl font-bold text-slate-700 border border-transparent focus:border-indigo-500 outline-none focus-ring">
                        @error('electric_price')
                            <p class="mt-2 text-sm font-bold text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-500 mb-1 block">Giá nước (VNĐ)</label>
                        <input type="number"
                               name="water_price"
                               placeholder="30000"
                               value="{{ old('water_price', $contract->water_price) }}"
                               class="w-full p-3 bg-slate-50 rounded-xl font-bold text-slate-700 border border-transparent focus:border-indigo-500 outline-none focus-ring">
                        @error('water_price')
                            <p class="mt-2 text-sm font-bold text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-500 mb-1 block">Tiền đặt cọc (VNĐ)</label>
                    <input type="number"
                           name="deposit"
                           required
                           placeholder="5000000"
                           value="{{ old('deposit', $contract->deposit) }}"
                           class="w-full p-3 bg-slate-100 rounded-xl font-black text-indigo-700 text-lg border border-transparent focus:border-indigo-500 outline-none focus-ring">
                    @error('deposit')
                        <p class="mt-2 text-sm font-bold text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-500 mb-1 block">Ghi chú dịch vụ</label>
                    <textarea name="service_note"
                              rows="2"
                              placeholder="Wifi, rác, xe..."
                              class="w-full p-3 bg-slate-50 rounded-xl font-bold text-slate-700 border border-transparent focus:border-indigo-500 outline-none focus-ring">{{ old('service_note', $contract->service_note) }}</textarea>
                    @error('service_note')
                        <p class="mt-2 text-sm font-bold text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-3 pt-6 border-t border-slate-100">
                    <button type="submit"
                            class="flex-1 bg-slate-900 text-white py-3.5 rounded-xl font-black uppercase tracking-widest text-sm hover:bg-indigo-600 transition-all shadow-lg shadow-indigo-100">
                        Cập nhật hợp đồng
                    </button>

                    <a href="{{ route('admin.contracts.index') }}"
                       class="px-8 inline-flex items-center justify-center text-slate-400 font-bold hover:text-slate-600">
                        Hủy
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const roomSelect = document.getElementById('roomSelect');
        const preview = document.getElementById('roomContractPreview');
        const list = document.getElementById('roomContractList');
        const noContract = document.getElementById('roomNoContract');
        const capacityText = document.getElementById('roomCapacityText');

        function renderRoomContracts() {
            if (!roomSelect || !preview || !list || !noContract || !capacityText) return;

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
        }

        if (roomSelect) {
            roomSelect.addEventListener('change', renderRoomContracts);
            renderRoomContracts();
        }
    });
</script>
@endsection
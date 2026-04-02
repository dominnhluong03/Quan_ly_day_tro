<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Building;
use App\Models\Contract;
use App\Models\Room;
use App\Models\RoomAsset;
use App\Models\RoomImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::with(['building', 'images', 'assets'])
            ->orderByDesc('id')
            ->get();

        foreach ($rooms as $room) {
            $room->current_people = Contract::where('room_id', $room->id)
                ->where('status', 'active')
                ->count();
        }

        $buildings = Building::orderBy('name')->get();

        return view('admin.rooms.index', compact('rooms', 'buildings'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'room_code' => 'required|string|max:20',
            'building_id' => 'required|exists:buildings,id',
            'floor' => 'nullable|integer|min:0',
            'area' => 'nullable|numeric|min:0',
            'max_people' => 'nullable|integer|min:1',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:empty,occupied,maintenance',
            'description' => 'nullable|string',
            'assets' => 'nullable|array',
            'assets.*' => 'string|max:100',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $validator->after(function ($validator) use ($request) {
            $exists = Room::where('room_code', $request->room_code)
                ->where('floor', $request->floor)
                ->exists();

            if ($exists) {
                $validator->errors()->add('room_code', 'Mã phòng đã tồn tại ở cùng tầng.');
                $validator->errors()->add('floor', 'Tầng này đã có mã phòng đó. Vui lòng chọn tầng khác hoặc đổi mã phòng.');
            }
        });

        $data = $validator->validate();

        $room = Room::create([
            'room_code' => $data['room_code'],
            'building_id' => $data['building_id'],
            'floor' => $data['floor'] ?? null,
            'area' => $data['area'] ?? null,
            'max_people' => $data['max_people'] ?? null,
            'price' => $data['price'],
            'status' => $data['status'],
            'description' => $data['description'] ?? null,
        ]);

        if (! empty($data['assets'])) {
            foreach ($data['assets'] as $assetName) {
                RoomAsset::create([
                    'room_id' => $room->id,
                    'name' => $assetName,
                    'quantity' => 1,
                    'status' => 'good',
                    'note' => null,
                ]);
            }
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $img) {
                $path = $img->store('rooms', 'public');

                RoomImage::create([
                    'room_id' => $room->id,
                    'image_path' => $path,
                ]);
            }
        }

        return redirect()
            ->route('admin.rooms.index')
            ->with('success', 'Thêm phòng thành công!');
    }

    public function edit(Room $room)
    {
        $room->load(['building', 'images', 'assets']);
        $buildings = Building::orderBy('name')->get();

        return view('admin.rooms.edit', compact('room', 'buildings'));
    }

    public function update(Request $request, Room $room)
    {
        $validator = Validator::make($request->all(), [
            'room_code' => 'required|string|max:20',
            'building_id' => 'required|exists:buildings,id',
            'floor' => 'nullable|integer|min:0',
            'area' => 'nullable|numeric|min:0',
            'max_people' => 'nullable|integer|min:1',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:empty,occupied,maintenance',
            'description' => 'nullable|string',
            'assets' => 'nullable|array',
            'assets.*' => 'string|max:100',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:4096',
            'delete_image_ids' => 'nullable|array',
            'delete_image_ids.*' => 'integer|exists:room_images,id',
        ]);

        $validator->after(function ($validator) use ($request, $room) {
            $exists = Room::where('room_code', $request->room_code)
                ->where('floor', $request->floor)
                ->where('id', '!=', $room->id)
                ->exists();

            if ($exists) {
                $validator->errors()->add('room_code', 'Mã phòng đã tồn tại ở cùng tầng.');
                $validator->errors()->add('floor', 'Tầng này đã có mã phòng đó. Vui lòng chọn tầng khác hoặc đổi mã phòng.');
            }
        });

        $data = $validator->validate();

        $room->update([
            'room_code' => $data['room_code'],
            'building_id' => $data['building_id'],
            'floor' => $data['floor'] ?? null,
            'area' => $data['area'] ?? null,
            'max_people' => $data['max_people'] ?? null,
            'price' => $data['price'],
            'status' => $data['status'],
            'description' => $data['description'] ?? null,
        ]);

        $room->assets()->delete();
        if (! empty($data['assets'])) {
            foreach ($data['assets'] as $assetName) {
                RoomAsset::create([
                    'room_id' => $room->id,
                    'name' => $assetName,
                    'quantity' => 1,
                    'status' => 'good',
                    'note' => null,
                ]);
            }
        }

        if (! empty($data['delete_image_ids'])) {
            $imagesToDelete = $room->images()
                ->whereIn('id', $data['delete_image_ids'])
                ->get();

            foreach ($imagesToDelete as $img) {
                if ($img->image_path && Storage::disk('public')->exists($img->image_path)) {
                    Storage::disk('public')->delete($img->image_path);
                }
                $img->delete();
            }
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $img) {
                $path = $img->store('rooms', 'public');

                RoomImage::create([
                    'room_id' => $room->id,
                    'image_path' => $path,
                ]);
            }
        }

        return redirect()
            ->route('admin.rooms.index')
            ->with('success', 'Cập nhật phòng thành công!');
    }

    public function destroy(Room $room)
    {
        $room->load(['images', 'contracts']);

        $hasActiveContract = Contract::where('room_id', $room->id)
            ->where('status', 'active')
            ->exists();

        if ($hasActiveContract) {
            return back()->with('error', 'Không thể xóa phòng vì phòng đang có người thuê và hợp đồng còn hiệu lực.');
        }

        foreach ($room->images as $img) {
            if ($img->image_path && Storage::disk('public')->exists($img->image_path)) {
                Storage::disk('public')->delete($img->image_path);
            }
        }

        $room->images()->delete();
        $room->assets()->delete();
        $room->delete();

        return back()->with('success', 'Đã xóa phòng');
    }
}
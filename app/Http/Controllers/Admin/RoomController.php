<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
<<<<<<< HEAD
=======
use Illuminate\Support\Facades\Validator;
>>>>>>> feb1f02 (first commit)

use App\Models\Room;
use App\Models\Building;
use App\Models\RoomImage;
use App\Models\RoomAsset;
<<<<<<< HEAD
=======
use App\Models\Contract;
>>>>>>> feb1f02 (first commit)

class RoomController extends Controller
{
    public function index()
    {
<<<<<<< HEAD
        $rooms = Room::with(['building','images'])
            ->orderByDesc('id')
            ->get();

        // 👇 Đếm số hợp đồng active theo room_id
        foreach ($rooms as $room) {
            $room->current_people = \App\Models\Contract::where('room_id', $room->id)
                ->where('status','active')
                ->count();
        }

        $buildings = \App\Models\Building::orderBy('name')->get();

        return view('admin.rooms.index', compact('rooms','buildings'));
=======
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
>>>>>>> feb1f02 (first commit)
    }

    public function store(Request $request)
    {
<<<<<<< HEAD
        $data = $request->validate([
            'room_code'   => 'required|string|max:20|unique:rooms,room_code',
=======
        $validator = Validator::make($request->all(), [
            'room_code'   => 'required|string|max:20',
>>>>>>> feb1f02 (first commit)
            'building_id' => 'required|exists:buildings,id',
            'floor'       => 'nullable|integer|min:0',
            'area'        => 'nullable|numeric|min:0',
            'max_people'  => 'nullable|integer|min:1',
            'price'       => 'required|numeric|min:0',
            'status'      => 'required|in:empty,occupied,maintenance',
            'description' => 'nullable|string',

            'assets'      => 'nullable|array',
            'assets.*'    => 'string|max:100',

            'images'      => 'nullable|array',
            'images.*'    => 'image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

<<<<<<< HEAD
        // 1) Lưu phòng
=======
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

>>>>>>> feb1f02 (first commit)
        $room = Room::create([
            'room_code'   => $data['room_code'],
            'building_id' => $data['building_id'],
            'floor'       => $data['floor'] ?? null,
            'area'        => $data['area'] ?? null,
            'max_people'  => $data['max_people'] ?? null,
            'price'       => $data['price'],
            'status'      => $data['status'],
            'description' => $data['description'] ?? null,
        ]);

<<<<<<< HEAD
        // 2) Lưu tiện nghi (assets[])
        if (!empty($data['assets'])) {
            foreach ($data['assets'] as $assetName) {
                RoomAsset::create([
                    'room_id'   => $room->id,
                    'name'      => $assetName,
                    'quantity'  => 1,
                    'status'    => 'good',
                    'note'      => null,
=======
        if (!empty($data['assets'])) {
            foreach ($data['assets'] as $assetName) {
                RoomAsset::create([
                    'room_id'  => $room->id,
                    'name'     => $assetName,
                    'quantity' => 1,
                    'status'   => 'good',
                    'note'     => null,
>>>>>>> feb1f02 (first commit)
                ]);
            }
        }

<<<<<<< HEAD
        // 3) Upload & lưu ảnh (images[])
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $img) {
                $path = $img->store('rooms', 'public'); // storage/app/public/rooms/...
=======
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $img) {
                $path = $img->store('rooms', 'public');
>>>>>>> feb1f02 (first commit)

                RoomImage::create([
                    'room_id'    => $room->id,
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
<<<<<<< HEAD
        $room->load(['building','images','assets']);
        $buildings = Building::orderByDesc('id')->get();

        return view('admin.rooms.edit', compact('room','buildings'));
=======
        $room->load(['building', 'images', 'assets']);
        $buildings = Building::orderBy('name')->get();

        return view('admin.rooms.edit', compact('room', 'buildings'));
>>>>>>> feb1f02 (first commit)
    }

    public function update(Request $request, Room $room)
    {
<<<<<<< HEAD
        $data = $request->validate([
            'room_code'   => 'required|string|max:20|unique:rooms,room_code,' . $room->id,
            'building_id' => 'required|exists:buildings,id',
            'floor'       => 'nullable|integer|min:0',
            'area'        => 'nullable|numeric|min:0',
            'max_people'  => 'nullable|integer|min:1',
            'price'       => 'required|numeric|min:0',
            'status'      => 'required|in:empty,occupied,maintenance',
            'description' => 'nullable|string',

            'assets'      => 'nullable|array',
            'assets.*'    => 'string|max:100',

            'images'      => 'nullable|array',
            'images.*'    => 'image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

=======
        $validator = Validator::make($request->all(), [
            'room_code'        => 'required|string|max:20',
            'building_id'      => 'required|exists:buildings,id',
            'floor'            => 'nullable|integer|min:0',
            'area'             => 'nullable|numeric|min:0',
            'max_people'       => 'nullable|integer|min:1',
            'price'            => 'required|numeric|min:0',
            'status'           => 'required|in:empty,occupied,maintenance',
            'description'      => 'nullable|string',

            'assets'           => 'nullable|array',
            'assets.*'         => 'string|max:100',

            'images'           => 'nullable|array',
            'images.*'         => 'image|mimes:jpg,jpeg,png,webp|max:4096',

            'delete_image_ids'   => 'nullable|array',
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

>>>>>>> feb1f02 (first commit)
        $room->update([
            'room_code'   => $data['room_code'],
            'building_id' => $data['building_id'],
            'floor'       => $data['floor'] ?? null,
            'area'        => $data['area'] ?? null,
            'max_people'  => $data['max_people'] ?? null,
            'price'       => $data['price'],
            'status'      => $data['status'],
            'description' => $data['description'] ?? null,
        ]);

<<<<<<< HEAD
        // Update assets: đơn giản nhất là xóa cũ tạo lại
        $room->assets()->delete();
        if (!empty($data['assets'])) {
            foreach ($data['assets'] as $assetName) {
                RoomAsset::create([
                    'room_id'   => $room->id,
                    'name'      => $assetName,
                    'quantity'  => 1,
                    'status'    => 'good',
                    'note'      => null,
=======
        // cập nhật tiện nghi
        $room->assets()->delete();

        if (!empty($data['assets'])) {
            foreach ($data['assets'] as $assetName) {
                RoomAsset::create([
                    'room_id'  => $room->id,
                    'name'     => $assetName,
                    'quantity' => 1,
                    'status'   => 'good',
                    'note'     => null,
>>>>>>> feb1f02 (first commit)
                ]);
            }
        }

<<<<<<< HEAD
        // Upload thêm ảnh (không xóa ảnh cũ)
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $img) {
                $path = $img->store('rooms', 'public');
=======
        // xóa ảnh cũ nếu được chọn
        if (!empty($data['delete_image_ids'])) {
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

        // thêm ảnh mới
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $img) {
                $path = $img->store('rooms', 'public');

>>>>>>> feb1f02 (first commit)
                RoomImage::create([
                    'room_id'    => $room->id,
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
<<<<<<< HEAD
        // Xóa ảnh + file
=======
        $room->load(['images', 'contracts']);

        // Kiểm tra phòng đang có hợp đồng còn hiệu lực / đang có người thuê
        $hasActiveContract = Contract::where('room_id', $room->id)
            ->where('status', 'active')
            ->exists();

        if ($hasActiveContract) {
            return back()->with('error', 'Không thể xóa phòng vì phòng đang có người thuê và hợp đồng còn hiệu lực.');
        }

>>>>>>> feb1f02 (first commit)
        foreach ($room->images as $img) {
            if ($img->image_path && Storage::disk('public')->exists($img->image_path)) {
                Storage::disk('public')->delete($img->image_path);
            }
        }

        $room->images()->delete();
        $room->assets()->delete();
<<<<<<< HEAD

=======
>>>>>>> feb1f02 (first commit)
        $room->delete();

        return back()->with('success', 'Đã xóa phòng');
    }
}
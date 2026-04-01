<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Building;

class BuildingController extends Controller
{
    // Danh sách
    public function index()
    {
        $buildings = Building::latest()->get();

        return view('admin.buildings.index', compact('buildings'));
    }

    // Lưu tòa nhà
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'house_number' => 'nullable',
            'street' => 'nullable',
            'ward' => 'nullable',
            'district' => 'nullable',
            'city' => 'nullable',
            'description' => 'nullable',
            'amenities' => 'nullable|array'
        ]);

        Building::create($data);

        return redirect()->back()->with('success', 'Đã thêm tòa nhà');
    }

    // Xóa
    public function destroy($id)
    {
        Building::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Đã xóa');
    }
}

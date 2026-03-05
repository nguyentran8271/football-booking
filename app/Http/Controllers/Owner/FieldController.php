<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Field;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FieldController extends Controller
{
    /**
     * Danh sách sân của owner
     */
    public function index()
    {
        $fields = auth()->user()->fields()->paginate(10);
        return view('owner.fields.index', compact('fields'));
    }

    /**
     * Form tạo sân mới
     */
    public function create()
    {
        return view('owner.fields.create');
    }

    /**
     * Lưu sân mới
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'province' => 'required|string',
            'ward' => 'required|string',
            'address_detail' => 'required|string',
            'price_per_hour' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'hotline' => 'nullable|string|regex:/^[0-9]{10}$/',
            'status' => 'required|in:active,inactive',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'hotline.regex' => 'Số điện thoại phải có đúng 10 chữ số.',
            'province.required' => 'Vui lòng chọn tỉnh/thành phố.',
            'ward.required' => 'Vui lòng chọn phường/xã.',
            'address_detail.required' => 'Vui lòng nhập địa chỉ chi tiết.',
        ]);

        // Ghép địa chỉ đầy đủ (2025: chỉ còn 2 cấp)
        $validated['address'] = $validated['address_detail'] . ', ' .
                                $validated['ward'] . ', ' .
                                $validated['province'];

        $validated['owner_id'] = auth()->id();

        // Upload ảnh
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('fields', 'public');
        }

        Field::create($validated);

        return redirect()->route('owner.dashboard')->with('success', 'Thêm sân thành công!');
    }

    /**
     * Form sửa sân
     */
    public function edit($id)
    {
        $field = Field::where('owner_id', auth()->id())->findOrFail($id);
        return view('owner.fields.edit', compact('field'));
    }

    /**
     * Cập nhật sân
     */
    public function update(Request $request, $id)
    {
        $field = Field::where('owner_id', auth()->id())->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'price_per_hour' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'hotline' => 'nullable|string|regex:/^[0-9]{10}$/',
            'status' => 'required|in:active,inactive',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'hotline.regex' => 'Số điện thoại phải có đúng 10 chữ số.',
        ]);

        // Upload ảnh mới
        if ($request->hasFile('image')) {
            // Xóa ảnh cũ
            if ($field->image) {
                Storage::disk('public')->delete($field->image);
            }
            $validated['image'] = $request->file('image')->store('fields', 'public');
        }

        $field->update($validated);

        return redirect()->route('owner.fields.index')->with('success', 'Cập nhật sân thành công!');
    }

    /**
     * Xóa sân
     */
    public function destroy($id)
    {
        $field = Field::where('owner_id', auth()->id())->findOrFail($id);

        // Xóa ảnh
        if ($field->image) {
            Storage::disk('public')->delete($field->image);
        }

        $field->delete();

        return redirect()->route('owner.fields.index')->with('success', 'Xóa sân thành công!');
    }
}

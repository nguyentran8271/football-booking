<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Field;
use App\Services\UploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FieldController extends Controller
{
    public function index()
    {
        $fields = auth()->user()->fields()->paginate(10);
        return view('owner.fields.index', compact('fields'));
    }

    public function create()
    {
        return view('owner.fields.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'province'       => 'required|string',
            'ward'           => 'required|string',
            'address_detail' => 'required|string',
            'price_per_hour' => 'required|numeric|min:0',
            'description'    => 'nullable|string',
            'hotline'        => 'nullable|string|regex:/^[0-9]{10}$/',
            'status'         => 'required|in:active,inactive',
            'image'          => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ], [
            'hotline.regex'          => 'Số điện thoại phải có đúng 10 chữ số.',
            'province.required'      => 'Vui lòng chọn tỉnh/thành phố.',
            'ward.required'          => 'Vui lòng chọn phường/xã.',
            'address_detail.required'=> 'Vui lòng nhập địa chỉ chi tiết.',
        ]);

        $validated['address']  = $validated['address_detail'] . ', ' . $validated['ward'] . ', ' . $validated['province'];
        $validated['owner_id'] = auth()->id();

        if ($request->hasFile('image')) {
            $validated['image'] = UploadService::upload($request->file('image'), 'fields');
        }

        Field::create($validated);

        return redirect()->route('owner.dashboard')->with('success', 'Thêm sân thành công!');
    }

    public function edit($id)
    {
        $field = Field::where('owner_id', auth()->id())->findOrFail($id);
        return view('owner.fields.edit', compact('field'));
    }

    public function update(Request $request, $id)
    {
        $field = Field::where('owner_id', auth()->id())->findOrFail($id);

        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'address'        => 'required|string',
            'price_per_hour' => 'required|numeric|min:0',
            'description'    => 'nullable|string',
            'hotline'        => 'nullable|string|regex:/^[0-9]{10}$/',
            'status'         => 'required|in:active,inactive',
            'image'          => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ], [
            'hotline.regex' => 'Số điện thoại phải có đúng 10 chữ số.',
        ]);

        if ($request->hasFile('image')) {
            if ($field->image) {
                UploadService::delete($field->image);
            }
            $validated['image'] = UploadService::upload($request->file('image'), 'fields');
        }

        $field->update($validated);

        return redirect()->route('owner.fields.index')->with('success', 'Cập nhật sân thành công!');
    }

    public function destroy($id)
    {
        $field = Field::where('owner_id', auth()->id())->findOrFail($id);

        if ($field->image) {
                UploadService::delete($field->image);
            }

        $field->delete();

        return redirect()->route('owner.fields.index')->with('success', 'Xóa sân thành công!');
    }
}

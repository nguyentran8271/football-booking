<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Field;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FieldController extends Controller
{
    public function index()
    {
        $fields = Field::with('owner')->paginate(20);
        return view('admin.fields.index', compact('fields'));
    }

    public function destroy($id)
    {
        $field = Field::findOrFail($id);

        if ($field->image) {
            Storage::disk('public')->delete($field->image);
        }

        $field->delete();

        return back()->with('success', 'Đã xóa sân.');
    }
}

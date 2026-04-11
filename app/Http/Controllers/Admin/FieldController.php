<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Field;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FieldController extends Controller
{
    public function index(Request $request)
    {
        $query = Field::with('owner');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhereHas('owner', function ($q) use ($request) {
                      $q->where('name', 'like', '%' . $request->search . '%');
                  });
        }

        $fields = $query->paginate(20)->withQueryString();
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

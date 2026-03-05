<?php

namespace App\Http\Controllers;

use App\Models\Field;
use Illuminate\Http\Request;

class FieldController extends Controller
{
    /**
     * Hiển thị danh sách sân
     */
    public function index(Request $request)
    {
        $query = Field::with('owner', 'reviews.user');

        // Tìm kiếm theo tên
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        // Lọc theo tỉnh/thành phố
        if ($request->has('province') && $request->province) {
            $query->where('address', 'like', "%{$request->province}%");
        }

        // Lọc theo xã/phường
        if ($request->has('ward') && $request->ward) {
            $query->where('address', 'like', "%{$request->ward}%");
        }

        $fields = $query->paginate(6)->appends($request->all());

        // Lấy settings cho trang fields
        $fieldsBanner = \App\Models\SiteSetting::get('fields_banner');
        $fieldsTitle = \App\Models\SiteSetting::get('fields_title') ?: 'Tìm Sân Bóng';
        $fieldsDescription = \App\Models\SiteSetting::get('fields_description') ?: 'Chọn sân phù hợp với bạn';

        // Lấy giải đấu đang mở
        $tournamentsQuery = \App\Models\Tournament::with(['field', 'teams'])
            ->where('status', '!=', 'finished');

        // Tìm kiếm giải đấu theo tên
        if ($request->has('tournament_search') && $request->tournament_search) {
            $tournamentsQuery->where('name', 'like', '%' . $request->tournament_search . '%');
        }

        // Tìm kiếm giải đấu theo tên sân
        if ($request->has('tournament_field') && $request->tournament_field) {
            $tournamentsQuery->whereHas('field', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->tournament_field . '%');
            });
        }

        $tournaments = $tournamentsQuery->orderBy('start_date', 'asc')
            ->limit(6)
            ->get();

        return view('fields.index', compact(
            'fields',
            'fieldsBanner',
            'fieldsTitle',
            'fieldsDescription',
            'tournaments'
        ));
    }

    /**
     * Hiển thị chi tiết sân
     */
    public function show($id)
    {
        $field = Field::with(['owner', 'reviews.user'])->findOrFail($id);

        return view('fields.show', compact('field'));
    }
}

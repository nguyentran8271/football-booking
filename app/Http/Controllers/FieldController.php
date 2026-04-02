<?php

namespace App\Http\Controllers;

use App\Models\Field;
use App\Models\Booking;
use App\Models\Review;
use Illuminate\Http\Request;

class FieldController extends Controller
{
    public function index(Request $request)
    {
        $query = Field::with('owner', 'reviews.user');

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        if ($request->has('province') && $request->province) {
            $query->where('address', 'like', "%{$request->province}%");
        }

        if ($request->has('ward') && $request->ward) {
            $query->where('address', 'like', "%{$request->ward}%");
        }

        $fields = $query->paginate(6)->appends($request->all());

        $fieldsBanner = \App\Models\SiteSetting::get('fields_banner');
        $fieldsTitle = \App\Models\SiteSetting::get('fields_title') ?: 'Tìm Sân Bóng';
        $fieldsDescription = \App\Models\SiteSetting::get('fields_description') ?: 'Chọn sân phù hợp với bạn';

        $tournamentsQuery = \App\Models\Tournament::with(['field', 'teams'])
            ->where('status', '!=', 'finished');

        if ($request->has('tournament_search') && $request->tournament_search) {
            $tournamentsQuery->where('name', 'like', '%' . $request->tournament_search . '%');
        }

        if ($request->has('tournament_field') && $request->tournament_field) {
            $tournamentsQuery->whereHas('field', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->tournament_field . '%');
            });
        }

        $tournaments = $tournamentsQuery->orderBy('start_date', 'asc')->limit(6)->get();

        return view('fields.index', compact(
            'fields', 'fieldsBanner', 'fieldsTitle', 'fieldsDescription', 'tournaments'
        ));
    }

    public function show($id)
    {
        $field = Field::with(['owner', 'reviews.user'])->findOrFail($id);

        $hasBooked = false;
        $hasReviewed = false;

        if (auth()->check()) {
            $hasBooked = Booking::where('user_id', auth()->id())
                ->where('field_id', $id)
                ->where('status', 'approved')
                ->exists();

            $hasReviewed = Review::where('user_id', auth()->id())
                ->where('field_id', $id)
                ->exists();
        }

        return view('fields.show', compact('field', 'hasBooked', 'hasReviewed'));
    }
}

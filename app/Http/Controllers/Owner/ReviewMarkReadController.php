<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewMarkReadController extends Controller
{
    public function __invoke(Request $request)
    {
        $fieldIds = auth()->user()->fields()->pluck('id');
        Review::whereIn('field_id', $fieldIds)->where('is_read', false)->update(['is_read' => true]);
        return back();
    }
}

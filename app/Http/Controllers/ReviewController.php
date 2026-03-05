<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Field;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    /**
     * Hiển thị trang đánh giá với thống kê
     */
    public function index(Request $request)
    {
        // Tính toán thống kê tổng quan
        $totalReviews = Review::count();
        $averageRating = Review::avg('rating') ?? 0;

        // Phân bố đánh giá theo số sao
        $ratingDistribution = Review::select('rating', DB::raw('count(*) as count'))
            ->groupBy('rating')
            ->orderBy('rating', 'desc')
            ->get()
            ->pluck('count', 'rating')
            ->toArray();

        // Đảm bảo có đủ 5 mức sao
        for ($i = 5; $i >= 1; $i--) {
            if (!isset($ratingDistribution[$i])) {
                $ratingDistribution[$i] = 0;
            }
        }
        ksort($ratingDistribution);
        $ratingDistribution = array_reverse($ratingDistribution, true);

        // Tính % khách hàng hài lòng (4-5 sao)
        $satisfiedCount = Review::whereIn('rating', [4, 5])->count();
        $satisfactionRate = $totalReviews > 0 ? round(($satisfiedCount / $totalReviews) * 100, 1) : 0;

        // Đánh giá chi tiết trung bình
        $detailedRatings = [
            'field_quality' => Review::avg('field_quality_rating') ?? 0,
            'lighting' => Review::avg('lighting_rating') ?? 0,
            'hygiene' => Review::avg('hygiene_rating') ?? 0,
            'staff' => Review::avg('staff_rating') ?? 0,
            'price' => Review::avg('price_rating') ?? 0,
        ];

        // Lấy danh sách reviews với filter
        $query = Review::with(['user', 'field']);

        // Filter theo số sao
        if ($request->has('rating') && $request->rating != '') {
            $query->where('rating', $request->rating);
        }

        // Filter có hình ảnh
        if ($request->has('with_images') && $request->with_images) {
            $query->whereNotNull('images')->where('images', '!=', '[]');
        }

        // Sắp xếp
        $sortBy = $request->get('sort', 'latest');
        switch ($sortBy) {
            case 'oldest':
                $query->oldest();
                break;
            case 'highest':
                $query->orderBy('rating', 'desc');
                break;
            case 'lowest':
                $query->orderBy('rating', 'asc');
                break;
            case 'helpful':
                $query->orderBy('helpful_count', 'desc');
                break;
            default:
                $query->latest();
        }

        $reviews = $query->paginate(10);

        return view('reviews.index', compact(
            'reviews',
            'totalReviews',
            'averageRating',
            'ratingDistribution',
            'satisfactionRate',
            'detailedRatings'
        ));
    }

    /**
     * Lưu đánh giá
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'field_id' => 'required|exists:fields,id',
            'rating' => 'required|integer|min:1|max:5',
            'field_quality_rating' => 'nullable|numeric|min:1|max:5',
            'lighting_rating' => 'nullable|numeric|min:1|max:5',
            'hygiene_rating' => 'nullable|numeric|min:1|max:5',
            'staff_rating' => 'nullable|numeric|min:1|max:5',
            'price_rating' => 'nullable|numeric|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'location' => 'nullable|string|max:255',
        ]);

        Review::create([
            'user_id' => auth()->id(),
            'field_id' => $validated['field_id'],
            'rating' => $validated['rating'],
            'field_quality_rating' => $validated['field_quality_rating'] ?? null,
            'lighting_rating' => $validated['lighting_rating'] ?? null,
            'hygiene_rating' => $validated['hygiene_rating'] ?? null,
            'staff_rating' => $validated['staff_rating'] ?? null,
            'price_rating' => $validated['price_rating'] ?? null,
            'comment' => $validated['comment'],
            'location' => $validated['location'] ?? null,
        ]);

        return back()->with('success', 'Cảm ơn bạn đã đánh giá!');
    }

    /**
     * Đánh dấu review hữu ích
     */
    public function markHelpful($id)
    {
        $review = Review::findOrFail($id);
        $review->increment('helpful_count');

        return response()->json([
            'success' => true,
            'helpful_count' => $review->helpful_count
        ]);
    }
}

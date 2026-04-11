<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Field;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $totalReviews = Review::whereNull('field_id')->count();
        $averageRating = Review::whereNull('field_id')->avg('rating') ?? 0;

        $ratingDistribution = Review::whereNull('field_id')
            ->select('rating', DB::raw('count(*) as count'))
            ->groupBy('rating')
            ->orderBy('rating', 'desc')
            ->get()
            ->pluck('count', 'rating')
            ->toArray();

        for ($i = 5; $i >= 1; $i--) {
            if (!isset($ratingDistribution[$i])) {
                $ratingDistribution[$i] = 0;
            }
        }
        ksort($ratingDistribution);
        $ratingDistribution = array_reverse($ratingDistribution, true);

        $satisfiedCount = Review::whereNull('field_id')->whereIn('rating', [4, 5])->count();
        $satisfactionRate = $totalReviews > 0 ? round(($satisfiedCount / $totalReviews) * 100, 1) : 0;

        $detailedRatings = [
            'field_quality' => Review::whereNull('field_id')->avg('field_quality_rating') ?? 0,
            'lighting'      => Review::whereNull('field_id')->avg('lighting_rating') ?? 0,
            'hygiene'       => Review::whereNull('field_id')->avg('hygiene_rating') ?? 0,
            'staff'         => Review::whereNull('field_id')->avg('staff_rating') ?? 0,
            'price'         => Review::whereNull('field_id')->avg('price_rating') ?? 0,
        ];

        $query = Review::with('user')->whereNull('field_id');

        if ($request->has('rating') && $request->rating != '') {
            $query->where('rating', $request->rating);
        }

        if ($request->has('with_images') && $request->with_images) {
            $query->whereNotNull('images')->where('images', '!=', '[]');
        }

        $sortBy = $request->get('sort', 'latest');
        switch ($sortBy) {
            case 'oldest':   $query->oldest(); break;
            case 'highest':  $query->orderBy('rating', 'desc'); break;
            case 'lowest':   $query->orderBy('rating', 'asc'); break;
            case 'helpful':  $query->orderBy('helpful_count', 'desc'); break;
            default:         $query->latest();
        }

        $reviews = $query->paginate(10);

        $hasWebReviewed = auth()->check()
            ? Review::where('user_id', auth()->id())->whereNull('field_id')->exists()
            : false;

        $userWebReview = auth()->check()
            ? Review::where('user_id', auth()->id())->whereNull('field_id')->first()
            : null;

        return view('reviews.index', compact(
            'reviews', 'totalReviews', 'averageRating',
            'ratingDistribution', 'satisfactionRate', 'detailedRatings', 'hasWebReviewed', 'userWebReview'
        ));
    }

    public function store(Request $request)
    {
        $type = $request->input('type', 'field');

        if ($type === 'web') {
            $validated = $request->validate([
                'rating'               => 'required|integer|min:1|max:5',
                'field_quality_rating' => 'nullable|numeric|min:1|max:5',
                'lighting_rating'      => 'nullable|numeric|min:1|max:5',
                'hygiene_rating'       => 'nullable|numeric|min:1|max:5',
                'staff_rating'         => 'nullable|numeric|min:1|max:5',
                'price_rating'         => 'nullable|numeric|min:1|max:5',
                'comment'              => 'nullable|string|max:1000',
            ]);

            Review::create([
                'user_id'              => auth()->id(),
                'field_id'             => null,
                'rating'               => $validated['rating'],
                'field_quality_rating' => $validated['field_quality_rating'] ?? null,
                'lighting_rating'      => $validated['lighting_rating'] ?? null,
                'hygiene_rating'       => $validated['hygiene_rating'] ?? null,
                'staff_rating'         => $validated['staff_rating'] ?? null,
                'price_rating'         => $validated['price_rating'] ?? null,
                'comment'              => $validated['comment'] ?? null,
            ]);

            return back()->with('success', 'Cảm ơn bạn đã đánh giá trải nghiệm!');
        }

        $validated = $request->validate([
            'field_id' => 'required|exists:fields,id',
            'rating'   => 'required|integer|min:1|max:5',
            'comment'  => 'nullable|string|max:1000',
        ]);

        $hasBooked = \App\Models\Booking::where('user_id', auth()->id())
            ->where('field_id', $validated['field_id'])
            ->where('status', 'approved')
            ->exists();

        if (!$hasBooked) {
            return back()->with('error', 'Bạn cần đặt và hoàn thành lịch tại sân này mới có thể đánh giá.');
        }

        $alreadyReviewed = Review::where('user_id', auth()->id())
            ->where('field_id', $validated['field_id'])
            ->exists();

        if ($alreadyReviewed) {
            return back()->with('error', 'Bạn đã đánh giá sân này rồi.');
        }

        Review::create([
            'user_id'  => auth()->id(),
            'field_id' => $validated['field_id'],
            'rating'   => $validated['rating'],
            'comment'  => $validated['comment'] ?? null,
        ]);

        return back()->with('success', 'Cảm ơn bạn đã đánh giá!');
    }

    public function update(Request $request, $id)
    {
        $review = Review::where('id', $id)->where('user_id', auth()->id())->firstOrFail();

        $validated = $request->validate([
            'rating'               => 'required|integer|min:1|max:5',
            'comment'              => 'nullable|string|max:1000',
            'field_quality_rating' => 'nullable|numeric|min:1|max:5',
            'lighting_rating'      => 'nullable|numeric|min:1|max:5',
            'hygiene_rating'       => 'nullable|numeric|min:1|max:5',
            'staff_rating'         => 'nullable|numeric|min:1|max:5',
            'price_rating'         => 'nullable|numeric|min:1|max:5',
        ]);

        $review->update($validated);

        return back()->with('success', 'Đã cập nhật đánh giá.');
    }

    public function destroy($id)
    {
        $review = Review::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        $review->delete();

        return back()->with('success', 'Đã xóa đánh giá.');
    }

    public function markHelpful($id)
    {
        $review = Review::findOrFail($id);

        $likedKey = 'review_liked_' . $id;
        $sessionLiked = session($likedKey, false);

        if ($sessionLiked) {
            $review->decrement('helpful_count');
            session()->forget($likedKey);
            $liked = false;
        } else {
            $review->increment('helpful_count');
            session([$likedKey => true]);
            $liked = true;
        }

        return response()->json([
            'success' => true,
            'helpful_count' => $review->fresh()->helpful_count,
            'liked' => $liked,
        ]);
    }
}

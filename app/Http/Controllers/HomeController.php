<?php

namespace App\Http\Controllers;

use App\Models\Field;
use App\Models\Post;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredFields = Field::withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->orderBy('reviews_avg_rating', 'desc')
            ->take(4)
            ->get();

        $posts = Post::latest()->take(6)->get();
        $totalPosts = Post::count();

        return view('home', compact('featuredFields', 'posts', 'totalPosts'));
    }

    public function loadMorePosts(Request $request)
    {
        $offset   = (int) $request->get('offset', 6);
        $limit    = (int) $request->get('limit', 5);
        $category = $request->get('category', 'all');

        $baseQuery = Post::latest();
        if ($category !== 'all') {
            $baseQuery->where('category', $category);
        }
        $total = $baseQuery->count();

        if ($offset === 0) {
            $posts = Post::latest()
                ->when($category !== 'all', fn($q) => $q->where('category', $category))
                ->take(6)
                ->get();

            $featured = null;
            if ($posts->isNotEmpty()) {
                $featuredPost = $posts->first();
                $featured = view('partials.featured-post', compact('featuredPost'))->render();
            }

            $html = '';
            foreach ($posts->slice(1) as $post) {
                $html .= view('partials.post-item', compact('post'))->render();
            }

            return response()->json([
                'html'       => $html,
                'featured'   => $featured,
                'hasMore'    => $total > 6,
                'nextOffset' => 6,
            ]);
        }

        $posts = Post::latest()
            ->when($category !== 'all', fn($q) => $q->where('category', $category))
            ->skip($offset)
            ->take($limit)
            ->get();

        $html = '';
        foreach ($posts as $post) {
            $html .= view('partials.post-item', compact('post'))->render();
        }

        return response()->json([
            'html'       => $html,
            'hasMore'    => ($offset + $limit) < $total,
            'nextOffset' => $offset + $limit,
        ]);
    }

    public function about()
    {
        $sections = \App\Models\AboutSection::orderBy('order')->get();
        return view('pages.about', compact('sections'));
    }

    public function policy()
    {
        return view('pages.policy');
    }

    public function forOwners()
    {
        $stats    = \App\Models\OwnerStat::orderBy('order')->get();
        $benefits = \App\Models\OwnerBenefit::orderBy('order')->get();
        $steps    = \App\Models\OwnerStep::orderBy('step_number')->get();
        $sections = \App\Models\OwnerSection::orderBy('order')->get();

        return view('pages.for-owners', compact('stats', 'benefits', 'steps', 'sections'));
    }
}

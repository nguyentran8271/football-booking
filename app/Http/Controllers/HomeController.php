<?php

namespace App\Http\Controllers;

use App\Models\Field;
use App\Models\Post;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Hiển thị trang chủ
     */
    public function index()
    {
        // Lấy 4 sân nổi bật (có rating cao nhất)
        $featuredFields = Field::withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->orderBy('reviews_avg_rating', 'desc')
            ->take(4)
            ->get();

        // Lấy tin tức mới nhất
        $posts = Post::latest()->take(6)->get();

        return view('home', compact('featuredFields', 'posts'));
    }

    /**
     * Trang giới thiệu
     */
    public function about()
    {
        $sections = \App\Models\AboutSection::orderBy('order')->get();
        return view('pages.about', compact('sections'));
    }

    /**
     * Trang chính sách
     */
    public function policy()
    {
        return view('pages.policy');
    }

    /**
     * Trang dành cho chủ sân
     */
    public function forOwners()
    {
        $stats = \App\Models\OwnerStat::orderBy('order')->get();
        $benefits = \App\Models\OwnerBenefit::orderBy('order')->get();
        $steps = \App\Models\OwnerStep::orderBy('step_number')->get();
        $sections = \App\Models\OwnerSection::orderBy('order')->get();

        return view('pages.for-owners', compact('stats', 'benefits', 'steps', 'sections'));
    }
}

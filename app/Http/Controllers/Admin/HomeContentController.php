<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeCard;
use App\Models\HomeStat;
use App\Models\FeaturedField;
use App\Services\UploadService;
use Illuminate\Http\Request;

class HomeContentController extends Controller
{
    public function index()
    {
        $cards = HomeCard::orderBy('order')->get();
        $stats = HomeStat::orderBy('order')->get();
        $featuredFields = FeaturedField::orderBy('order')->get();

        return view('admin.home-content.index', compact('cards', 'stats', 'featuredFields'));
    }

    public function storeCard(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $maxOrder = HomeCard::max('order') ?? -1;
        HomeCard::create([
            'title'       => $request->title,
            'description' => $request->description,
            'order'       => $maxOrder + 1,
        ]);

        return response()->json(['success' => true]);
    }

    public function updateCard(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $card = HomeCard::findOrFail($id);
        $card->update(['title' => $request->title, 'description' => $request->description]);

        return response()->json(['success' => true]);
    }

    public function deleteCard($id)
    {
        HomeCard::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }

    public function storeStat(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'value' => 'required|string|max:255',
        ]);

        $maxOrder = HomeStat::max('order') ?? -1;

        HomeStat::create([
            'title' => $request->title,
            'value' => $request->value,
            'order' => $maxOrder + 1,
        ]);

        return response()->json(['success' => true]);
    }

    public function updateStat(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'value' => 'required|string|max:255',
        ]);

        $stat = HomeStat::findOrFail($id);
        $stat->update([
            'title' => $request->title,
            'value' => $request->value,
        ]);

        return response()->json(['success' => true]);
    }

    public function deleteStat($id)
    {
        HomeStat::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }

    public function storeField(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'price'       => 'required|numeric|min:1',
            'hotline'     => 'nullable|string|max:20',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $maxOrder = FeaturedField::max('order') ?? -1;
        $data = $request->only(['title', 'description', 'price', 'hotline']);
        $data['order'] = $maxOrder + 1;

        if ($request->hasFile('image')) {
            $data['image'] = UploadService::upload($request->file('image'), 'featured-fields');
        }

        FeaturedField::create($data);
        return response()->json(['success' => true]);
    }

    public function updateField(Request $request, $id)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'price'       => 'required|numeric|min:1',
            'hotline'     => 'nullable|string|max:20',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $field = FeaturedField::findOrFail($id);
        $data = $request->only(['title', 'description', 'price', 'hotline']);

        if ($request->hasFile('image')) {
            UploadService::delete($field->image);
            $data['image'] = UploadService::upload($request->file('image'), 'featured-fields');
        }

        $field->update($data);
        return response()->json(['success' => true]);
    }

    public function deleteField($id)
    {
        $field = FeaturedField::findOrFail($id);
        UploadService::delete($field->image);
        $field->delete();
        return response()->json(['success' => true]);
    }

    public function storeAboutSection(Request $request)
    {
        $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
            'layout'  => 'required|in:image-left,image-right',
            'image'   => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $maxOrder = \App\Models\AboutSection::max('order') ?? -1;
        $data = $request->only(['title', 'content', 'layout']);
        $data['order'] = $maxOrder + 1;

        if ($request->hasFile('image')) {
            $data['image'] = UploadService::upload($request->file('image'), 'about-sections');
        }

        \App\Models\AboutSection::create($data);
        return response()->json(['success' => true]);
    }

    public function updateAboutSection(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'layout' => 'required|in:image-left,image-right',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $section = \App\Models\AboutSection::findOrFail($id);

        $section->title = $validated['title'];
        $section->content = $validated['content'];
        $section->layout = $validated['layout'];

        if ($request->hasFile('image')) {
            UploadService::delete($section->image);
            $section->image = UploadService::upload($request->file('image'), 'about-sections');
        }

        $section->save();

        return response()->json(['success' => true, 'message' => 'Section đã được cập nhật thành công!']);
    }

    public function deleteAboutSection($id)
    {
        $section = \App\Models\AboutSection::findOrFail($id);
        UploadService::delete($section->image);
        $section->delete();
        return response()->json(['success' => true]);
    }
}

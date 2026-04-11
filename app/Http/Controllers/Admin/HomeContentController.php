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

        $data = $request->all();
        $maxOrder = HomeCard::max('order') ?? -1;
        $data['order'] = $maxOrder + 1;

        HomeCard::create($data);

        return redirect()->back()->with('success', 'Card đã được thêm thành công!');
    }

    public function updateCard(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $card = HomeCard::findOrFail($id);
        $data = $request->all();
        $data['order'] = $request->input('order', $card->order);

        $card->update($data);

        return redirect()->back()->with('success', 'Card đã được cập nhật thành công!');
    }

    public function deleteCard($id)
    {
        $card = HomeCard::findOrFail($id);
        $card->delete();

        return redirect()->back()->with('success', 'Card đã được xóa thành công!');
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
        $stat = HomeStat::findOrFail($id);
        $stat->delete();

        return redirect()->back()->with('success', 'Số liệu đã được xóa thành công!');
    }

    public function storeField(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'price'       => 'required|numeric|min:1',
            'hotline'     => 'nullable|string|max:20',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();
        $maxOrder = FeaturedField::max('order') ?? -1;
        $data['order'] = $maxOrder + 1;

        if ($request->hasFile('image')) {
            $data['image'] = UploadService::upload($request->file('image'), 'featured-fields');
        }

        FeaturedField::create($data);

        return redirect()->back()->with('success', 'Sân nổi bật đã được thêm thành công!');
    }

    public function updateField(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:1',
            'hotline' => 'nullable|string|max:20',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $field = FeaturedField::findOrFail($id);
        $data = $request->all();
        $data['order'] = $request->input('order', $field->order);

        if ($request->hasFile('image')) {
            UploadService::delete($field->image);
            $data['image'] = UploadService::upload($request->file('image'), 'featured-fields');
        }

        $field->update($data);

        return redirect()->back()->with('success', 'Sân nổi bật đã được cập nhật thành công!');
    }

    public function deleteField($id)
    {
        $field = FeaturedField::findOrFail($id);

        UploadService::delete($field->image);
        $field->delete();

        return redirect()->back()->with('success', 'Sân nổi bật đã được xóa thành công!');
    }

    public function storeAboutSection(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'layout' => 'required|in:image-left,image-right',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();
        $maxOrder = \App\Models\AboutSection::max('order') ?? -1;
        $data['order'] = $maxOrder + 1;

        if ($request->hasFile('image')) {
            $data['image'] = UploadService::upload($request->file('image'), 'about-sections');
        }

        \App\Models\AboutSection::create($data);

        return redirect()->back()->with('success', 'Section đã được thêm thành công!');
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

        return redirect()->back()->with('success', 'Section đã được xóa thành công!');
    }
}

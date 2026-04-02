<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OwnerStat;
use App\Models\OwnerBenefit;
use App\Models\OwnerStep;
use App\Models\OwnerSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OwnerPageController extends Controller
{
    public function index()
    {
        $stats = OwnerStat::orderBy('order')->get();
        $benefits = OwnerBenefit::orderBy('order')->get();
        $steps = OwnerStep::orderBy('step_number')->get();
        $sections = OwnerSection::orderBy('order')->get();

        return view('admin.owner-page.index', compact('stats', 'benefits', 'steps', 'sections'));
    }

    public function storeStat(Request $request)
    {
        $validated = $request->validate([
            'number' => 'required|string|max:50',
            'label' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
            'order' => 'required|integer',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('owner-stats', 'public');
        }

        OwnerStat::create($validated);

        return back()->with('success', 'Đã thêm stat thành công!');
    }

    public function updateStat(Request $request, $id)
    {
        $stat = OwnerStat::findOrFail($id);

        $validated = $request->validate([
            'number' => 'required|string|max:50',
            'label' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
            'order' => 'required|integer',
        ]);

        if ($request->hasFile('image')) {
            if ($stat->image) {
                Storage::disk('public')->delete($stat->image);
            }
            $validated['image'] = $request->file('image')->store('owner-stats', 'public');
        }

        $stat->update($validated);

        return back()->with('success', 'Đã cập nhật stat thành công!');
    }

    public function deleteStat($id)
    {
        $stat = OwnerStat::findOrFail($id);

        if ($stat->image) {
            Storage::disk('public')->delete($stat->image);
        }

        $stat->delete();

        return back()->with('success', 'Đã xóa stat thành công!');
    }

    public function storeBenefit(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'order' => 'required|integer',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('owner-benefits', 'public');
        }

        OwnerBenefit::create($validated);

        return back()->with('success', 'Đã thêm benefit thành công!');
    }

    public function updateBenefit(Request $request, $id)
    {
        $benefit = OwnerBenefit::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'order' => 'required|integer',
        ]);

        if ($request->hasFile('image')) {
            if ($benefit->image) {
                Storage::disk('public')->delete($benefit->image);
            }
            $validated['image'] = $request->file('image')->store('owner-benefits', 'public');
        }

        $benefit->update($validated);

        return back()->with('success', 'Đã cập nhật benefit thành công!');
    }

    public function deleteBenefit($id)
    {
        $benefit = OwnerBenefit::findOrFail($id);

        if ($benefit->image) {
            Storage::disk('public')->delete($benefit->image);
        }

        $benefit->delete();

        return back()->with('success', 'Đã xóa benefit thành công!');
    }

    public function storeStep(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'step_number' => 'required|integer|min:1',
        ]);

        OwnerStep::create($validated);

        return back()->with('success', 'Đã thêm bước thành công!');
    }

    public function updateStep(Request $request, $id)
    {
        $step = OwnerStep::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'step_number' => 'required|integer|min:1',
        ]);

        $step->update($validated);

        return back()->with('success', 'Đã cập nhật bước thành công!');
    }

    public function deleteStep($id)
    {
        $step = OwnerStep::findOrFail($id);
        $step->delete();

        return back()->with('success', 'Đã xóa bước thành công!');
    }

    public function storeSection(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'image_position' => 'required|in:left,right',
            'order' => 'required|integer',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('owner-sections', 'public');
        }

        OwnerSection::create($validated);

        return back()->with('success', 'Đã thêm section thành công!');
    }

    public function updateSection(Request $request, $id)
    {
        $section = OwnerSection::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'image_position' => 'required|in:left,right',
            'order' => 'required|integer',
        ]);

        if ($request->hasFile('image')) {
            if ($section->image) {
                Storage::disk('public')->delete($section->image);
            }
            $validated['image'] = $request->file('image')->store('owner-sections', 'public');
        }

        $section->update($validated);

        return back()->with('success', 'Đã cập nhật section thành công!');
    }

    public function deleteSection($id)
    {
        $section = OwnerSection::findOrFail($id);

        if ($section->image) {
            Storage::disk('public')->delete($section->image);
        }

        $section->delete();

        return back()->with('success', 'Đã xóa section thành công!');
    }
}

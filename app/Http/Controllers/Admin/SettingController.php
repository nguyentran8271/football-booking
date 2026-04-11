<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Services\UploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $aboutSections = \App\Models\AboutSection::orderBy('order')->get();
        return view('admin.settings.index', compact('aboutSections'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'nullable|string|max:255',
            'site_slogan' => 'nullable|string|max:255',
            'site_phone' => 'nullable|string|max:20',
            'site_email' => 'nullable|email|max:255',
            'site_hotline' => 'nullable|string|max:20',
            'site_address' => 'nullable|string',
            'site_description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'auth_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'auth_background' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'login_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'login_background' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'register_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'register_background' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'hero_title' => 'nullable|string|max:255',
            'hero_description' => 'nullable|string|max:255',
            'about_title' => 'nullable|string|max:255',
            'about_description' => 'nullable|string',
            'hero_banners' => 'nullable|array|max:5',
            'hero_banners.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120',
            'fields_banner' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'about_banner' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'reviews_banner' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'owner_banner' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'fields_title' => 'nullable|string|max:255',
            'fields_description' => 'nullable|string|max:255',
            'about_page_title' => 'nullable|string|max:255',
            'about_content' => 'nullable|string',
            'reviews_title' => 'nullable|string|max:255',
            'reviews_description' => 'nullable|string|max:255',
            'owners_title' => 'nullable|string|max:255',
            'owners_description' => 'nullable|string|max:255',
            'owners_content' => 'nullable|string',
            'cards' => 'nullable|array',
            'cards.*.title' => 'nullable|string|max:255',
            'cards.*.description' => 'nullable|string',
            'stats' => 'nullable|array',
            'stats.*.value' => 'nullable|string|max:50',
            'stats.*.title' => 'nullable|string|max:255',
            'fields' => 'nullable|array',
            'fields.*.title' => 'nullable|string|max:255',
            'fields.*.description' => 'nullable|string',
            'fields.*.price' => 'nullable|numeric|min:0',
            'fields.*.hotline' => 'nullable|string|max:20',
            'sections' => 'nullable|array',
            'sections.*.title' => 'nullable|string|max:255',
            'sections.*.content' => 'nullable|string',
            'sections.*.layout' => 'nullable|in:image-left,image-right',
        ]);

        $uploadedFiles = [];

        if ($request->has('cards')) {
            foreach ($request->cards as $cardId => $cardData) {
                $card = \App\Models\HomeCard::find($cardId);
                if ($card) {
                    $card->update([
                        'title' => $cardData['title'] ?? $card->title,
                        'description' => $cardData['description'] ?? $card->description,
                    ]);
                }
            }
        }

        if ($request->has('stats')) {
            foreach ($request->stats as $statId => $statData) {
                $stat = \App\Models\HomeStat::find($statId);
                if ($stat) {
                    $stat->update([
                        'value' => $statData['value'] ?? $stat->value,
                        'title' => $statData['title'] ?? $stat->title,
                    ]);
                }
            }
        }

        if ($request->has('fields')) {
            foreach ($request->fields as $fieldId => $fieldData) {
                $field = \App\Models\FeaturedField::find($fieldId);
                if ($field) {
                    $field->update([
                        'title' => $fieldData['title'] ?? $field->title,
                        'description' => $fieldData['description'] ?? $field->description,
                        'price' => $fieldData['price'] ?? $field->price,
                        'hotline' => $fieldData['hotline'] ?? $field->hotline,
                    ]);
                }
            }
        }

        if ($request->has('sections')) {
            foreach ($request->sections as $sectionId => $sectionData) {
                $section = \App\Models\AboutSection::find($sectionId);
                if ($section) {
                    $section->update([
                        'title' => $sectionData['title'] ?? $section->title,
                        'content' => $sectionData['content'] ?? $section->content,
                        'layout' => $sectionData['layout'] ?? $section->layout,
                    ]);
                }
            }
        }

        if ($request->has('remove_banners') && $request->remove_banners) {
            $existingBanners = SiteSetting::get('hero_banners');
            $bannerArray = $existingBanners ? json_decode($existingBanners, true) : [];

            $removeIndexes = json_decode($request->remove_banners, true);
            if (is_array($removeIndexes)) {
                rsort($removeIndexes);
                foreach ($removeIndexes as $index) {
                    if (isset($bannerArray[$index])) {
                        UploadService::delete($bannerArray[$index]);
                        unset($bannerArray[$index]);
                    }
                }
                $bannerArray = array_values($bannerArray); // Re-index array
                SiteSetting::set('hero_banners', json_encode($bannerArray));
            }
        }

        if ($request->hasFile('hero_banners')) {
            $existingBanners = SiteSetting::get('hero_banners');
            $bannerArray = $existingBanners ? json_decode($existingBanners, true) : [];

            foreach ($request->file('hero_banners') as $file) {
                if (count($bannerArray) < 5) {
                    $path = UploadService::upload($file, 'settings/banners');
                    $bannerArray[] = $path;
                }
            }

            SiteSetting::set('hero_banners', json_encode($bannerArray));
            unset($validated['hero_banners']);
        }

        foreach ($validated as $key => $value) {
            if (in_array($key, ['cards', 'stats', 'fields', 'sections'])) {
                continue;
            }

            if (in_array($key, ['logo', 'auth_logo', 'auth_background', 'login_logo', 'login_background', 'register_logo', 'register_background', 'fields_banner', 'about_banner', 'reviews_banner', 'owner_banner']) && $request->hasFile($key)) {
                $oldImage = SiteSetting::get($key);
                UploadService::delete($oldImage);
                $value = UploadService::upload($request->file($key), 'settings');
                $uploadedFiles[] = $key;
            }

            if ($value !== null) {
                SiteSetting::set($key, $value);
            }
        }

        $message = 'Cập nhật cài đặt thành công!';
        if (!empty($uploadedFiles)) {
            $message .= ' Đã upload: ' . implode(', ', $uploadedFiles);
        }

        return back()->with('success', $message);
    }

    public function storeOwnerStat(Request $request)
    {
        $validated = $request->validate([
            'number' => 'required|string|max:50',
            'label'  => 'required|string|max:255',
        ]);

        $maxOrder = \App\Models\OwnerStat::max('order') ?? 0;
        $validated['order'] = $maxOrder + 1;

        \App\Models\OwnerStat::create($validated);

        return back()->with('success', 'Đã thêm stat thành công!');
    }

    public function updateOwnerStat(Request $request, $id)
    {
        $stat = \App\Models\OwnerStat::findOrFail($id);

        $validated = $request->validate([
            'number' => 'required|string|max:50',
            'label' => 'required|string|max:255',
        ]);

        $stat->update($validated);

        return back()->with('success', 'Đã cập nhật stat thành công!');
    }

    public function deleteOwnerStat($id)
    {
        $stat = \App\Models\OwnerStat::findOrFail($id);

        if ($stat->image) {
            UploadService::delete($stat->image);
        }

        return back()->with('success', 'Đã xóa stat thành công!');
    }

    public function storeOwnerBenefit(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'image'       => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = UploadService::upload($request->file('image'), 'owner-benefits');
        }

        $maxOrder = \App\Models\OwnerBenefit::max('order') ?? 0;
        $validated['order'] = $maxOrder + 1;

        \App\Models\OwnerBenefit::create($validated);

        return back()->with('success', 'Đã thêm benefit thành công!');
    }

    public function updateOwnerBenefit(Request $request, $id)
    {
        $benefit = \App\Models\OwnerBenefit::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            UploadService::delete($benefit->image);
            $validated['image'] = UploadService::upload($request->file('image'), 'owner-benefits');
        }

        $benefit->update($validated);

        return response()->json(['success' => true, 'message' => 'Đã cập nhật benefit thành công!']);
    }

    public function deleteOwnerBenefit($id)
    {
        $benefit = \App\Models\OwnerBenefit::findOrFail($id);

        if ($benefit->image) {
            UploadService::delete($benefit->image);
        }

        $benefit->delete();

        return back()->with('success', 'Đã xóa benefit thành công!');
    }

    public function storeOwnerStep(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'step_number' => 'required|integer|min:1',
        ]);

        \App\Models\OwnerStep::create($validated);

        return back()->with('success', 'Đã thêm bước thành công!');
    }

    public function updateOwnerStep(Request $request, $id)
    {
        $step = \App\Models\OwnerStep::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'step_number' => 'required|integer|min:1',
        ]);

        $step->update($validated);

        return back()->with('success', 'Đã cập nhật bước thành công!');
    }

    public function deleteOwnerStep($id)
    {
        $step = \App\Models\OwnerStep::findOrFail($id);
        $step->delete();

        return back()->with('success', 'Đã xóa bước thành công!');
    }

    public function storeOwnerSection(Request $request)
    {
        $validated = $request->validate([
            'title'          => 'required|string|max:255',
            'content'        => 'required|string',
            'image'          => 'nullable|image|max:2048',
            'image_position' => 'required|in:left,right',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = UploadService::upload($request->file('image'), 'owner-sections');
        }

        $maxOrder = \App\Models\OwnerSection::max('order') ?? 0;
        $validated['order'] = $maxOrder + 1;

        \App\Models\OwnerSection::create($validated);

        return back()->with('success', 'Đã thêm section thành công!');
    }

    public function updateOwnerSection(Request $request, $id)
    {
        $section = \App\Models\OwnerSection::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'image_position' => 'required|in:left,right',
        ]);

        if ($request->hasFile('image')) {
            UploadService::delete($section->image);
            $validated['image'] = UploadService::upload($request->file('image'), 'owner-sections');
        }

        $section->update($validated);

        return response()->json(['success' => true, 'message' => 'Đã cập nhật section thành công!']);
    }

    public function deleteOwnerSection($id)
    {
        $section = \App\Models\OwnerSection::findOrFail($id);

        if ($section->image) {
            UploadService::delete($section->image);
        }

        $section->delete();

        return back()->with('success', 'Đã xóa section thành công!');
    }
}

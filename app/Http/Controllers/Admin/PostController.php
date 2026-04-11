<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Services\UploadService;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::latest()->paginate(20);
        return view('admin.posts.index', compact('posts'));
    }

    public function create()
    {
        return view('admin.posts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'    => 'required|string|max:255',
            'category' => 'required|in:trong_nuoc,ngoai_nuoc',
            'content'  => 'required|string',
            'image'    => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = UploadService::upload($request->file('image'), 'posts');
        }

        Post::create($validated);

        return redirect()->route('admin.posts.index')->with('success', 'Thêm bài viết thành công!');
    }

    public function edit($id)
    {
        $post = Post::findOrFail($id);
        return view('admin.posts.edit', compact('post'));
    }

    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        $validated = $request->validate([
            'title'    => 'required|string|max:255',
            'category' => 'required|in:trong_nuoc,ngoai_nuoc',
            'content'  => 'required|string',
            'image'    => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
        ]);

        if ($request->hasFile('image')) {
            UploadService::delete($post->image);
            $validated['image'] = UploadService::upload($request->file('image'), 'posts');
        }

        $post->update($validated);

        return redirect()->route('admin.posts.index')->with('success', 'Cập nhật bài viết thành công!');
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        UploadService::delete($post->image);
        $post->delete();
        return back()->with('success', 'Đã xóa bài viết.');
    }

    public function uploadImage(Request $request)
    {
        $request->validate(['file' => 'required|image|mimes:jpeg,png,jpg,gif|max:4096']);
        $url = UploadService::upload($request->file('file'), 'posts/content');
        $displayUrl = UploadService::url($url) ?? $url;
        return response()->json(['location' => $displayUrl]);
    }
}

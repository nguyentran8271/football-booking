<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            $validated['image'] = $request->file('image')->store('posts', 'public');
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
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }
            $validated['image'] = $request->file('image')->store('posts', 'public');
        }

        $post->update($validated);

        return redirect()->route('admin.posts.index')->with('success', 'Cập nhật bài viết thành công!');
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }
        $post->delete();
        return back()->with('success', 'Đã xóa bài viết.');
    }

    public function uploadImage(Request $request)
    {
        $request->validate(['file' => 'required|image|mimes:jpeg,png,jpg,gif|max:4096']);
        $path = $request->file('file')->store('posts/content', 'public');
        return response()->json(['location' => asset('storage/' . $path)]);
    }
}

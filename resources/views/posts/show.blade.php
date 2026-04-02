@extends('layouts.app')

@section('title', $post->title)

@push('styles')
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
<style>
.post-content { font-size: 16px; line-height: 1.8; color: #333; }
.post-content .ql-align-center { text-align: center; }
.post-content .ql-align-right { text-align: right; }
.post-content .ql-align-justify { text-align: justify; }
.post-content img { max-width: 100%; height: auto; border-radius: 8px; display: block; }
.post-content .ql-align-center img { margin: 0 auto; }
</style>
@endpush

@section('content')
<div class="container" style="max-width:860px; margin:40px auto; padding:0 20px;">
    <a href="{{ url()->previous() }}" style="display:inline-flex; align-items:center; gap:6px; color:#28a745; text-decoration:none; margin-bottom:24px; font-size:14px;">
        ← Quay lại
    </a>

    @if($post->image)
    <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}"
         style="width:100%; max-height:420px; object-fit:cover; border-radius:12px; margin-bottom:28px;">
    @endif

    <p style="color:#888; font-size:13px; margin-bottom:12px;">{{ $post->created_at->format('d/m/Y') }}</p>
    <h1 style="font-size:28px; font-weight:700; margin-bottom:24px; line-height:1.4;">{{ $post->title }}</h1>

    <div class="post-content ql-editor" style="padding:0;">
        {!! $post->content !!}
    </div>
</div>
@endsection

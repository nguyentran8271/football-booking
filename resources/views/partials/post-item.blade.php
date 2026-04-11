<a href="{{ route('posts.show', $post->id) }}" style="text-decoration:none; color:inherit; display:block;">
    <div class="post-item">
        @if($post->image)
        <img src="{{ storage_url($post->image) }}" alt="{{ $post->title }}" class="post-thumb">
        @endif
        <div class="post-info">
            <h4 class="post-item-title">{{ $post->title }}</h4>
            <span class="post-date">{{ $post->created_at->format('d/m/Y') }}</span>
        </div>
    </div>
</a>

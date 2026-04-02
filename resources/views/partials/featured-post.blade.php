<a href="{{ route('posts.show', $featuredPost->id) }}" style="text-decoration:none; color:inherit; display:block;">
    <div class="featured-badge">TIN TỨC</div>
    <h3 class="featured-title">{{ $featuredPost->title }}</h3>

    @if($featuredPost->image)
    <div class="featured-image-wrapper">
        <img src="{{ asset('storage/' . $featuredPost->image) }}" alt="{{ $featuredPost->title }}" class="featured-image">
    </div>
    @endif

    <p class="featured-excerpt">{{ Str::limit(strip_tags($featuredPost->content), 200) }}</p>
</a>

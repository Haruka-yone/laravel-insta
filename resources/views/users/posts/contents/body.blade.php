{{-- clickable image --}}
{{-- clickable image --}}
<div class="container p-0">
    @if ($post->images->count() > 1)
        {{-- Bootstrap Carousel for multiple images --}}
        <div id="carousel-{{ $post->id }}" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                @foreach ($post->images as $index => $image)
                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                        <a href="{{ route('post.show', $post->id) }}">
                            <img src="{{ $image->image }}" class="d-block w-100 uniform-img"
                                alt="post image {{ $post->id }}">
                        </a>
                    </div>
                @endforeach
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carousel-{{ $post->id }}"
                data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carousel-{{ $post->id }}"
                data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>
    @elseif($post->images->count() === 1)
        {{-- Single image --}}
        <a href="{{ route('post.show', $post->id) }}">
            <img src="{{ $post->images->first()->image }}" class="w-100 uniform-img"
                alt="post image {{ $post->id }}">
        </a>
    @endif
</div>

<div class="card-body">
    {{-- heard button + no. of likes + categories --}}
    <div class="row align-items-center">
        <div class="col-auto">
            @if ($post->isLiked())
                <form action="{{ route('like.destroy', $post->id) }}" method="post">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm shadow-none p-0">
                        <i class="fa-solid fa-heart text-danger"></i>
                    </button>
                </form>
            @else
                <form action="{{ route('like.store', $post->id) }}" method="post">
                    @csrf
                    <button type="submit" class="btn btn-sm shadow-none p-0">
                        <i class="fa-regular fa-heart"></i>
                    </button>
                </form>
            @endif
        </div>
        <div class="col-auto px-0">
            <span>{{ $post->likes->count() }}</span>
        </div>
        <div class="col text-end">
            @forelse ($post->categoryPost as $category_post)
                <div class="badge bg-secondary bg-opacity-50">
                    {{ $category_post->category->name }}
                </div>
            @empty
                <div class="badge bg-dark">
                    Uncategorized
                </div>
            @endforelse
        </div>
    </div>

    {{-- owner + desciription --}}
    <a href="{{ route('profile.show', $post->user->id) }}" class="text-decoration-none text-dark fw-bold">
        {{ $post->user->name }}
    </a>
    &nbsp;
    <p class="d-inline fw-light">{{ $post->description }}</p>
    <p class="text-uppercase text-muted xsmall">{{ date('M d, Y', strtotime($post->created_at)) }}</p>

    {{-- comments --}}
    @include('users.posts.contents.comments')
</div>

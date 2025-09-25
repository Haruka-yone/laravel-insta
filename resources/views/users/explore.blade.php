@extends('layouts.app')

@section('title', 'Explore Category')

@section('content')
    <h2 class="text-center" style="color:#B0A695;">Search Posts by Category</h2>

    <form action="{{ route('categories.explore') }}" method="post" class="d-flex justify-content-center mt-4">
        @csrf
        <select name="category_id" class="form-select w-25">
             <option value=""  disabled {{ empty($selected) ? 'selected' : '' }}>
                Select Category
            </option>
            <option value="all" {{ (isset($selected) && $selected === 'all') ? 'selected' : '' }}>
                All Categories
            </option>
            @foreach ($all_categories as $category)
                <option value="{{ $category->id }}" {{ (isset($selected) && $selected == $category->id) ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
        <button type="submit" class="btn ms-2" style="background-color:#776B5D; color:#fff; border:none;">
            <i class="fa-solid fa-magnifying-glass"></i>
        </button>
    </form>

    @isset($posts)
        @if (count($posts) > 0)
            <div class="row mt-5">
                @foreach ($posts as $post)
                    <div class="col-4 col-md-3 mb-3">
                        <a href="{{ route('post.show', $post->id) }}" class="d-block">
                            @if ($post->images->count() > 1)
                                {{-- Bootstrap Carousel (with controls, fixed height) --}}
                                <div id="carouselPost{{ $post->id }}" class="carousel slide" data-bs-ride="carousel">
                                    <div class="carousel-inner">
                                        @foreach ($post->images as $key => $image)
                                            <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                                <img src="{{ $image->image }}" 
                                                    alt="Post image {{ $post->id }}" 
                                                    class="d-block w-100 rounded shadow-sm mt-3"
                                                    style="height: 200px; object-fit: cover;">
                                            </div>
                                        @endforeach
                                    </div>

                                    {{-- Controls --}}
                                    <button class="carousel-control-prev" type="button"
                                        data-bs-target="#carouselPost{{ $post->id }}" data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon"></span>
                                    </button>
                                    <button class="carousel-control-next" type="button"
                                        data-bs-target="#carouselPost{{ $post->id }}" data-bs-slide="next">
                                        <span class="carousel-control-next-icon"></span>
                                    </button>
                                </div>
                            @else
                                {{-- Single Image --}}
                                <img src="{{ $post->images->first()->image }}" 
                                    alt="Post image {{ $post->id }}" 
                                    class="img-fluid rounded shadow-sm mt-3"
                                    style="width: 100%; height: 200px; object-fit: cover;">
                            @endif
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <div class="d-flex justify-content-center align-items-center" style="height: 60vh;">
                <h2 class="text-center" style="color:#776B5D;">No posts found.</h2>
            </div>
        @endif
    @endisset
   
@endsection
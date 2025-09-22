@extends('layouts.app')

@section('title', 'Explore Category')

@section('content')
    <h2 class="text-center text-secondary">Search Posts by Category</h2>

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
        <button type="submit" class="btn btn-secondary ms-2">
            <i class="fa-solid fa-helicopter"></i>
        </button>
    </form>

    @isset($posts)
        @if (count($posts) > 0)
            <div class="row mt-5">
                @foreach ($posts as $post)
                    <div class="col-4 col-md-3 mb-3">
                        <a href="{{ route('post.show', $post->id) }}">
                            <img src="{{ $post->image }}" alt="image" class="img-fluid rounded shadow-sm mt-3" style="width: 100%; height: 200px; object-fit: cover;">
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <div class="d-flex justify-content-center align-items-center" style="height: 60vh;">
                <h2 class="text-muted text-center ">No posts found.</h2>
            </div>
        @endif
    @endisset
   
@endsection
@extends('layouts.app')

@section('title', 'Admin Posts')

@section('content')

    <form action="{{ route('admin.posts')}}" style="width:500px" class="mb-5" id="filter-form">
        {{-- search --}}
        <input type="search" name="search" value="{{ request('search')}}" class="d-inline form-control form-control-sm mb-3" placeholder="Search Category..." autofocus>
        
        {{-- check box --}}
        <div>
            <h4 class="h4 fst-italic" style="font-family: Helvetica, Arial, sans-serif; color: #c7a45dd8 ">Select category</h4>
            @foreach ($all_categories as $category)
                <div class="form-check form-check-inline">
                    <input type="checkbox" name="categories[]" id="{{ $category->name }}" value="{{$category->id }}" class="form-check-input" {{ in_array($category->id, $selectedCategories ?? []) ? 'checked' : '' }} onchange="document.getElementById('filter-form').submit();">
                    <label for="{{ $category->name }}" class="form-check-label fw-bold">{{ $category->name }}</label>
                </div>
            @endforeach
        </div>
    </form>

    <table class="table table-hover align-middle bg-white border text-secondary">
        <thead class="small table-primary text-secondary">
            <tr>
                <th></th>
                <th></th>
                <th>CATEGORY</th>
                <th>OWNER</th>
                <th>CREATED AT</th>
                <th>STATUS</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($all_posts as $post)
                <tr>
                    <td>{{ $post->id }}</td>
                    <td>
                        <img src="{{ $post->image }}" alt="post id {{ $post->id }}" class="mx-auto avatar-lg">
                    </td>
                    <td>
                        @if ($post->categoryPost->isNotEmpty())
                            @foreach ($post->categoryPost as $category_post)
                                <div class="badge bg-secondary bg-opacity-50">
                                    {{ $category_post->category->name }}
                                </div>
                            @endforeach
                        @else
                            <div class="badge bg-dark">
                                Uncategorized
                            </div>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('profile.show', $post->user->id) }}" class="text-decoration-none text-dark fw-bold">{{ $post->user->name }}</a>
                    </td>
                    <td>{{ $post->created_at }}</td>
                    <td>
                         @if ($post->trashed())
                            <i class="fa-solid fa-circle-minus text-secondary"></i>&nbsp; Hidden
                        @else
                            <i class="fa-solid fa-circle text-primary"></i>&nbsp; Visible
                        @endif
                    </td>
                    <td>
                        @if (Auth::user()->id !== $post->user->id)
                            <div class="dropdown">
                                <button class="btn btn-sm" data-bs-toggle="dropdown">
                                    <i class="fa-solid fa-ellipsis"></i>
                                </button>
                                <div class="dropdown-menu">
                                    @if ($post->trashed())
                                        <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#activate-user-{{ $post->user->id }}">
                                            <i class="fa-solid fa-eye"></i> Unhide post {{ $post->id }}
                                        </button>
                                    @else
                                        <button class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#deactivate-user-{{ $post->user->id }}">
                                            <i class="fa-solid fa-eye-slash"></i> Hide post {{ $post->id }}
                                        </button>
                                    @endif  
                                </div>
                            </div>
                        @include('admin.posts.modals.status')
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $all_posts->Links() }}
@endsection
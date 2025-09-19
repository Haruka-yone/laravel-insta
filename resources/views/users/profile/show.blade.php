@extends('layouts.app')

@section('title', $user->name . ' • Profile')

@section('content')
    {{-- Profile Header --}}
    @include('users.profile.header')

    {{-- User Posts --}}
    <div class="mt-5">
        @if ($user->posts->isNotEmpty())
            <div class="row g-4">
                @foreach ($user->posts as $post)
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <a href="{{ route('post.show', $post->id) }}" 
                           class="d-block position-relative overflow-hidden rounded-4 shadow-sm post-card">
                            <img src="{{ $post->image }}" 
                                 alt="Post {{ $post->id }}" 
                                 class="img-fluid w-100 h-100" 
                                 style="object-fit: cover; aspect-ratio: 1/1;">
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center mt-5">
                <i class="fa-regular fa-image mb-3" style="font-size: 4rem; color:#B0A695;"></i>
                <h5 class="fw-semibold" style="color:#776B5D;">No Posts Yet</h5>
                {{-- <p class="text-muted">Add some post!</p> --}}
            </div>
        @endif
    </div>
@endsection

@push('styles')
<style>
    .post-card {
        border: 3px solid #F3EEEA; /* soft beige border */
        background-color: #F3EEEA;
        transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
    }

    .post-card:hover {
        transform: scale(1.03);
        border-color: #B0A695; /* accent on hover */
        box-shadow: 0 6px 16px rgba(119, 107, 93, 0.3); /* warm brown glow */
    }
</style>
@endpush

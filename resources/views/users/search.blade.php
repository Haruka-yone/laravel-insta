@extends('layouts.app')

@section('title', 'Explore People')

@section('content')
<div class="row justify-content-center fade-in">
    <div class="col-6">

        {{-- Search header --}}
        <div class="d-flex align-items-center mb-4">
            <i class="fa-solid fa-magnifying-glass me-2" style="color:#B0A695;"></i>
            <p class="h5 text-muted mb-0">
                Search results for 
                <span class="fw-bold" style="color:#B0A695;">"{{ $search }}"</span>
            </p>
        </div>

        {{-- Users List --}}
        @forelse ($users as $user)
        {{-- hiro --}}
            <div class="d-flex align-items-center justify-content-between mb-3 p-3 rounded-3 shadow-sm search-card">
                {{-- Avatar --}}
                <div class="d-flex align-items-center">
                    <a href="{{ route('profile.show', $user->id) }}" class="me-3">
                        @if ($user->avatar)
                            <img src="{{ $user->avatar }}" 
                                 alt="{{ $user->name }}" 
                                 class="rounded-circle avatar-md hover-zoom">
                        @else
                            <i class="fa-solid fa-circle-user icon-md" style="color:#B0A695;"></i>
                        @endif
                    </a>

                    {{-- User Info --}}
                    <div class="text-truncate">
                        <a href="{{ route('profile.show', $user->id) }}" 
                           class="fw-bold text-decoration-none d-block" style="color:#333;">
                           {{ $user->name }}
                        </a>
                        <small class="text-muted">{{ $user->email }}</small>
                    </div>
                </div>

                {{-- Follow/Unfollow Button --}}
                @if ($user->id !== Auth::user()->id)
                    <div>
                        @if ($user->isFollowed())
                            <form action="{{ route('follow.destroy', $user->id) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="btn btn-sm fw-bold rounded-pill px-3 py-1 btn-outline-secondary">
                                    Following
                                </button>
                            </form>
                        @else
                            <form action="{{ route('follow.store', $user->id) }}" method="post">
                                @csrf
                                <button type="submit" 
                                        class="btn btn-sm fw-bold rounded-pill px-3 py-1 fancy-btn">
                                    Follow
                                </button>
                            </form>
                        @endif
                    </div>
                @endif
            </div>
        @empty
            {{-- No results --}}
            <div class="text-center py-5">
                <i class="fa-regular fa-circle-xmark icon-md mb-3" style="color:#B0A695;"></i>
                <p class="lead text-muted">No users found matching "<span class="fw-bold">{{ $search }}</span>".</p>
            </div>
        @endforelse
    </div>
</div>
@endsection

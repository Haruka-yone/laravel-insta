@extends('layouts.app')

@section('title', 'Followers')

@section('content')
    @include('users.profile.header')

    <div style="margin-top: 100px">
        @if ($user->followers->isNotEmpty()) 
            <div class="row justify-content-center">
                <div class="col-4">
                    <h3 class="text-muted text-center">Followers</h3>
                    @foreach ($user->followers as $follower)
                        <div class="row align-items-center mt-3">
                            <div class="col-auto">
                                @if ($follower->follower->avatar)
                                    <img src="{{ $follower->follower->avatar }}" alt="{{ $follower->follower->name }}"
                                        class="rounded-circle mx-auto avatar-md">
                                @else
                                    <i class="fa-solid fa-circle-user text-secondary d-block text-center icon-md"></i>
                                @endif
                            </div>
                            <div class="col ps-0">
                                <a href="{{ route('profile.show', $follower->follower->id) }}"
                                    class="text-decoration-none text-dark fw-bold">
                                    {{ $follower->follower->name }}
                                </a>
                            </div>
                            <div class="col">
                                @if ($follower->follower->id === Auth::user()->id)
                                    
                                @else
                                    @if ($follower->follower->isFollowed())
                                        <form action="{{ route('follow.destroy', $follower->follower->id) }}" method="post">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-secondary btn-sm fw-bold">Following</button>
                                        </form>
                                    @else
                                        <form action="{{ route('follow.store', $follower->follower->id) }}" method="post">
                                            @csrf
                                            <button type="submit" class="btn btn-primary btn-sm fw-bold">Follow</button>
                                        </form>
                                    @endif
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <h3 class="text-muted text-center">No followers Yet</h3>
        @endif
    </div>
@endsection

@extends('layouts.app')

@section('title', 'Following')

@section('content')
    @include('users.profile.header')

    <div style="margin-top: 100px">
        @if ($user->following->isNotEmpty())
            <div class="row justify-content-center">
                <div class="col-4">
                    <h3 class="text-muted text-center">Following</h3>
                    @foreach ($user->following as $following)
                        <div class="row align-items-center mt-3">
                            <div class="col-auto">
                                @if ($following->following->avatar)
                                    <img src="{{ $following->following->avatar }}" alt="{{ $following->following->name }}" class="rounded-circle mx-auto avatar-sm">
                                @else
                                    <i class="fa-solid fa-circle-user text-secondary d-block text-center icon-sm"></i>
                                @endif
                            </div>
                            <div class="col ps-0">
                                <a href="{{ route('profile.show', $following->following->id) }}" class="text-decoration-none text-dark fw-bold">{{ $following->following->name }}</a>
                            </div>
                            <div class="col">
                                @if ($following->following->id === Auth::user()->id)
                                    
                                @else
                                    @if ($following->following->isFollowed())
                                        <form action="{{ route('follow.destroy', $following->following->id) }}" method="post">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-secondary btn-sm fw-bold">Following</button>
                                        </form>
                                    @else
                                        <form action="{{ route('follow.store', $following->following->id) }}" method="post">
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
            </div>
        @else
            <h3 class="text-muted text-center">No following Yet</h3>
        @endif
    </div>
@endsection
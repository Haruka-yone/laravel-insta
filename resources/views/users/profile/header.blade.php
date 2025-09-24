<style>
    /* Hover effect for profile header */
    .profile-header {
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .profile-header:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
    }

    .edit-button {
        transition: transform 0.30s ease, box-shadow 0.30s ease;
    }

    .edit-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 22px rgba(0, 0, 0, 0.12)
    }
</style>

<div class="row align-items-center mb-4 p-4 rounded-4 shadow-sm profile-header" style="background-color:#F3EEEA;">
    {{-- Avatar --}}
    <div class="col-md-3 text-center mb-3 mb-md-0">
        @if ($user->avatar)
            <img src="{{ $user->avatar }}" 
                 alt="{{ $user->name }}" 
                 class="rounded-circle shadow-sm avatar-lg"
                 style="width: 150px; height: 150px; object-fit: cover; border: 4px solid #B0A695;">
        @else
            <i class="fa-solid fa-circle-user d-block mx-auto" 
               style="font-size: 150px; color:#B0A695;"></i>
        @endif
    </div>

    {{-- User Info --}}
    <div class="col-md-9">
        <div class="d-flex align-items-center mb-3 flex-wrap gap-2">
            <h2 class="h3 mb-0 me-3 fw-bold" style="color:#776B5D;">{{ $user->name }}</h2>

            {{-- Follow/Edit button --}}
            @if (Auth::user()->id === $user->id)
                <a href="{{ route('profile.edit') }}" 
                   class="btn btn-sm fw-semibold rounded-pill px-3 shadow-sm edit-button"
                   style="background-color:#B0A695; color:white;">
                   <i class="fa-solid fa-pen me-1"></i> Edit Profile
                </a>
            @else
                @if ($user->isFollowed())
                    <form action="{{ route('follow.destroy', $user->id) }}" method="post" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="btn btn-sm fw-semibold rounded-pill px-3 shadow-sm"
                                style="background-color:#776B5D; color:white;">
                                Following
                        </button>
                    </form>
                @else
                    <form action="{{ route('follow.store', $user->id) }}" method="post" class="d-inline">
                        @csrf
                        <button type="submit" 
                                class="btn btn-sm fw-semibold rounded-pill px-3 shadow-sm"
                                style="background-color:#B0A695; color:white;">
                                Follow
                        </button>
                    </form>
                @endif
            @endif
        </div>

        {{-- Stats --}}
        <div class="d-flex gap-4 mb-3">
            <a href="{{ route('profile.show', $user->id) }}" 
               class="text-decoration-none fw-semibold" style="color:#776B5D;">
                <strong>{{ $user->posts->count() }}</strong> 
                <span class="text-muted">posts</span>
            </a>
            <a href="{{ route('profile.followers', $user->id) }}" 
               class="text-decoration-none fw-semibold" style="color:#776B5D;">
                <strong>{{ $user->followers->count() }}</strong> 
                <span class="text-muted">{{ $user->followers->count() == 1 ? 'follower' : 'followers' }}</span>
            </a>
            <a href="{{ route('profile.following', $user->id) }}" 
               class="text-decoration-none fw-semibold" style="color:#776B5D;">
                <strong>{{ $user->following->count() }}</strong> 
                <span class="text-muted">following</span>
            </a>
        </div>

        {{-- Bio / Introduction --}}
        @if ($user->introduction)
            <p class="fw-semibold mb-0" style="color:#776B5D; white-space: pre-line;">
                {{ $user->introduction }}
            </p>
        @endif
    </div>
</div>

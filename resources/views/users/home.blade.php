@extends('layouts.app')

@section('title', 'Home')

@section('content')
    @include('users.stories.show')

    <div class="row gx-5">
        {{-- ===== MAIN POSTS FEED ===== --}}
        <div class="col-8">
            @forelse ($home_posts as $post)
                <div class="card mb-4 shadow-sm border-0 rounded-3 post-card">
                    {{-- title --}}
                    @include('users.posts.contents.title')
                    {{-- body --}}
                    @include('users.posts.contents.body')
                </div>
            @empty
                <div class="text-center py-5 fade-in">
                    <i class="fa-regular fa-image icon-lg mb-3" style="color:#B0A695;"></i>
                    <h2 class="fw-bold" style="color:#B0A695;">Share Photos</h2>
                    <p class="text-secondary">When you share photos, they'll appear on your profile.</p>
                    <a href="{{ route('post.create') }}" class="btn rounded-pill px-4 fancy-btn">
                        Share your first photo
                    </a>
                </div>
            @endforelse
        </div>

        {{-- ===== SIDEBAR ===== --}}
        <div class="col-4 fade-in">
            {{-- Profile Overview --}}
            <div class="d-flex align-items-center mb-5 shadow-sm rounded-3 py-3 px-3 profile-card">
                <div class="me-3">
                    <a href="{{ route('profile.show', Auth::user()->id) }}">
                        @if (Auth::user()->avatar)
                            <img src="{{ Auth::user()->avatar }}" alt="{{ Auth::user()->name }}"
                                class="rounded-circle avatar-md hover-zoom">
                        @else
                            <i class="fa-solid fa-circle-user icon-md" style="color:#B0A695;"></i>
                        @endif
                    </a>
                </div>
                <div>
                    <a href="{{ route('profile.show', Auth::user()->id) }}" class="fw-bold d-block text-decoration-none"
                        style="color:#333;">
                        {{ Auth::user()->name }}
                    </a>
                    <p class="mb-0 small text-muted">{{ Auth::user()->email }}</p>
                </div>
            </div>

            {{-- Suggestions --}}
            @if ($suggested_users)
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <p class="fw-bold mb-0" style="color:#B0A695;">Suggested For You</p>
                    <a href="#" class="fw-bold small text-decoration-none" style="color:#333;" data-bs-toggle="modal"
                        data-bs-target="#suggestionsModal">
                        See All
                    </a>

                </div>

                @foreach ($suggested_users as $user)
                    <div class="d-flex align-items-center mb-2 p-2 rounded-2 suggestion-card">
                        <div class="me-2">
                            <a href="{{ route('profile.show', $user->id) }}">
                                @if ($user->avatar)
                                    <img src="{{ $user->avatar }}" alt="{{ $user->name }}"
                                        class="rounded-circle avatar-sm hover-zoom">
                                @else
                                    <i class="fa-solid fa-circle-user icon-sm" style="color:#B0A695;"></i>
                                @endif
                            </a>
                        </div>
                        <div class="flex-grow-1 truncate">
                            <a href="{{ route('profile.show', $user->id) }}" class="fw-bold text-decoration-none"
                                style="color:#333;">
                                {{ $user->name }}
                            </a>
                        </div>
                        <div>
                            <form action="{{ route('follow.store', $user->id) }}" method="post">
                                @csrf
                                <button type="submit" class="btn btn-sm rounded-pill px-3 py-1 border-0 fancy-btn">
                                    Follow
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    <!-- Suggestions Modal -->
    <div class="modal fade" id="suggestionsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-md">
            <div class="modal-content rounded-3 shadow">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold" style="color:#B0A695;">Suggested Users</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    @foreach ($all_suggested_users as $user)
                        <div class="d-flex align-items-center mb-3 p-2 rounded-2 suggestion-card">
                            <div class="me-3">
                                <a href="{{ route('profile.show', $user->id) }}">
                                    @if ($user->avatar)
                                        <img src="{{ $user->avatar }}" alt="{{ $user->name }}"
                                            class="rounded-circle avatar-sm hover-zoom">
                                    @else
                                        <i class="fa-solid fa-circle-user icon-sm" style="color:#B0A695;"></i>
                                    @endif
                                </a>
                            </div>
                            <div class="flex-grow-1">
                                <a href="{{ route('profile.show', $user->id) }}" class="fw-bold text-decoration-none"
                                    style="color:#333;">
                                    {{ $user->name }}
                                </a>
                                <p class="mb-0 small text-muted">{{ $user->email }}</p>
                            </div>
                            <div>
                                <form action="{{ route('follow.store', $user->id) }}" method="post">
                                    @csrf
                                    <button type="submit" class="btn btn-sm rounded-pill px-3 py-1 border-0 fancy-btn">
                                        Follow
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.comment-form').forEach(form => {
            form.addEventListener('submit', async (e) => {
                e.preventDefault(); // ページリロードを止める

                const postId = form.dataset.postId;
                const url = form.action;
                const formData = new FormData(form);

                try {
                    const res = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: formData
                    });

                    const data = await res.json();

                    if (data.success) {
                        const commentList = form.closest('.mt-3').querySelector('.list-group');
                        const newComment = document.createElement('li');
                        newComment.classList.add('list-group-item', 'border-0', 'p-0', 'mb-2');

                        let deleteFormHtml = "";
                        if (data.can_delete) {
                            deleteFormHtml = `
                                <form action="/comment/${data.comment.id}/destroy" method="post" class="delete-comment-form" data-comment-id="${data.comment.id}">
                                    <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="border-0 bg-transparent text-danger p-0 xsmall">Delete</button>
                                </form>
                            `;
                        }

                        newComment.innerHTML = `
                            <a href="/profile/${data.comment.user.id}" class="text-decoration-none text-dark fw-bold">
                                ${data.comment.user.name}
                            </a>
                            &nbsp;
                            <p class="d-inline fw-light">${data.comment.body}</p>
                            <br>
                            <span class="text-uppercase text-muted xsmall">just now</span>
                            ${deleteFormHtml}
                        `;

                        if (commentList) {
                            commentList.prepend(newComment);
                        }

                        form.reset(); // フォームをクリア
                    }
                } catch (err) {
                    console.error(err);
                }
            });
        });
    });

    document.addEventListener('DOMContentLoaded', () => {
        document.body.addEventListener('submit', async (e) => {
            if (!e.target.classList.contains('delete-comment-form')) return; // delete-comment-form 以外は無視
            e.preventDefault();

            const form = e.target;
            const url = form.action;
            const formData = new FormData(form);

            // LaravelにDELETEリクエストだと伝える
            formData.append('_method', 'DELETE');
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

            try {
                const res = await fetch(url, {
                    method: 'POST', // LaravelにDELETEを伝えるためPOSTにする
                    headers: {
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await res.json();

                if (data.success) {
                    const commentItem = form.closest('li');
                    if (commentItem) {
                        commentItem.remove();
                    }
                }
            } catch (err) {
                console.error("Delete failed:", err);
            }
        });
    });

    document.addEventListener('DOMContentLoaded', () => {
        document.body.addEventListener('submit', async (e) => {
            if (!e.target.classList.contains('like-form')) return;
            e.preventDefault();

            const form = e.target;
            const url = form.action;
            const method = form.querySelector('input[name="_method"]')?.value || 'POST';
            const formData = new FormData(form);

            try {
                const res = await fetch(url, {
                    method: method, // POST or DELETE
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: formData
                });

                const data = await res.json();

                if (data.success) {
                    const icon = form.querySelector('i');

                    if (data.liked) {
                        // ❤️ いいね済みに変更
                        icon.classList.remove('fa-regular');
                        icon.classList.add('fa-solid', 'text-danger');

                        if (!form.querySelector('input[name="_method"]')) {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = '_method';
                            input.value = 'DELETE';
                            form.appendChild(input);
                        }
                        form.action = `/like/${form.dataset.postId}/destroy`;

                    } else {
                        // 🤍 いいね解除に変更
                        icon.classList.remove('fa-solid', 'text-danger');
                        icon.classList.add('fa-regular');

                        const methodInput = form.querySelector('input[name="_method"]');
                        if (methodInput) methodInput.remove();
                        form.action = `/like/${form.dataset.postId}/store`;
                    }

                    // カウント更新
                    const countEl = document.querySelector(`.like-count[data-post-id="${form.dataset.postId}"]`);
                    if (countEl) {
                        countEl.textContent = data.count;
                    }
                }
            } catch (err) {
                console.error("Like toggle failed:", err);
            }
        });
    });

    </script>
@endsection


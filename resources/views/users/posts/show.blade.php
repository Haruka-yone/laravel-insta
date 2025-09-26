@extends('layouts.app')

@section('title', 'Show Post')

@section('content')
    <style>
        .col-4 {
            overflow-y: scroll;
        }

        .card-body {
            position: absolute;
            top: 65px;
        }

        /* 🔹 Make all post images uniform */
        .uniform-img {
            height: 500px; /* adjust as needed */
            object-fit: cover;
            width: 100%;
        }
    </style>

    <div class="row border shadow">
        <div class="col p-0 border-end">
            <div class="col p-0 border-end">
                @if ($post->images->count() > 1)
                    {{-- Bootstrap Carousel --}}
                    <div id="carouselPost{{ $post->id }}" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @foreach ($post->images as $key => $image)
                                <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                    <img src="{{ $image->image }}" 
                                         alt="Post image {{ $post->id }}" 
                                         class="d-block uniform-img">
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
                         class="uniform-img">
                @endif
            </div>
        </div>

        <div class="col-4 px-0 bg-white">
            <div class="card border-0">
                <div class="card-header bg-white py-3">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <a href="{{ route('profile.show', $post->user->id) }}">
                                @if ($post->user->avatar)
                                    <img src="{{ $post->user->avatar }}" alt="{{ $post->user->name }}"
                                        class="rounded-circle avatar-sm">
                                @else
                                    <i class="fa-solid fa-circle-user text-secondary icon-sm"></i>
                                @endif
                            </a>
                        </div>
                        <div class="col ps-0">
                            <a href="{{ route('profile.show', $post->user->id) }}"
                                class="text-decoration-none text-dark">{{ $post->user->name }}</a>
                        </div>
                        <div class="col-auto">
                            {{-- If you are the OWNER of the post --}}
                            @if (Auth::user()->id === $post->user->id)
                                <div class="dropdown">
                                    <button class="btn btn-sm shadow-none" data-bs-toggle="dropdown">
                                        <i class="fa-solid fa-ellipsis"></i>
                                    </button>

                                    <div class="dropdown-menu">
                                        <a href="{{ route('post.edit', $post->id) }}" class="dropdown-item">
                                            <i class="fa-regular fa-pen-to-square"></i> Edit
                                        </a>
                                        <button class="dropdown-item text-danger" data-bs-toggle="modal"
                                            data-bs-target="#delete-post-{{ $post->id }}">
                                            <i class="fa-regular fa-trash-can"></i> Delete
                                        </button>
                                    </div>
                                    {{-- include modal --}}
                                    @include('users.posts.contents.modals.delete')
                                </div>
                            @else
                                {{-- Follow/Unfollow --}}
                                @if ($post->user->isFollowed())
                                    <form action="{{ route('follow.destroy', $post->user->id) }}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="btn btn-outline-secondary btn-sm fw-bold">Following</button>
                                    </form>
                                @else
                                    <form action="{{ route('follow.store', $post->user->id) }}" method="post">
                                        @csrf
                                        <button type="submit" class="btn btn-primary btn-sm fw-bold">Follow</button>
                                    </form>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card-body w-100">
                    {{-- like + categories --}}
                    <div class="row align-items-center">
                        <div class="col-auto">
                            @if ($post->isLiked())
                                <form action="{{ route('like.destroy', $post->id) }}" method="post" class="like-form" data-post-id="{{ $post->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm shadow-none p-0">
                                        <i class="fa-solid fa-heart text-danger"></i>
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('like.store', $post->id) }}" method="post" class="like-form" data-post-id="{{ $post->id }}">
                                    @csrf
                                    <button type="submit" class="btn btn-sm shadow-none p-0">
                                        <i class="fa-regular fa-heart"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                        <div class="col-auto px-0">
                            <span class="like-count" data-post-id="{{ $post->id }}">{{ $post->likes->count() }}</span>
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

                    {{-- owner + description --}}
                    <a href="{{ route('profile.show', $post->user->id) }}" class="text-decoration-none text-dark fw-bold">
                        {{ $post->user->name }}
                    </a>
                    &nbsp;
                    <p class="d-inline fw-light">{{ $post->description }}</p>
                    <p class="text-uppercase text-muted xsmall">{{ date('M d,Y', strtotime($post->created_at)) }}</p>

                    {{-- comments --}}
                    <div class="mt-4">
                        <form action="{{ route('comment.store', $post->id) }}" method="post" class="comment-form" data-post-id="{{ $post->id }}">
                            @csrf
                            <div class="input-group">
                                <textarea name="comment_body{{ $post->id }}" cols="30" rows="1"
                                    class="form-control form-control-sm"
                                    placeholder="Add a comment...">{{ old('comment_body' . $post->id) }}</textarea>
                                <button type="submit" class="btn btn-outline-secondary btn-sm" title="Post">
                                    <i class="fa-regular fa-paper-plane"></i>
                                </button>
                            </div>
                            {{-- Error --}}
                            @error('comment_body' . $post->id)
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </form>

                        {{-- Show all comments --}}
                        @if ($post->comments->isNotEmpty())
                            <ul class="list-group mt-2">
                                @foreach ($post->comments as $comment)
                                    <li class="list-group-item border-0 p-0 mb-2">
                                        <a href="{{ route('profile.show', $comment->user->id) }}"
                                            class="text-decoration-none text-dark fw-bold">{{ $comment->user->name }}</a>
                                        &nbsp;
                                        <p class="d-inline fw-light">{{ $comment->body }}</p>

                                        <form action="{{ route('comment.destroy', $comment->id) }}" method="post" class="delete-comment-form" data-comment-id="{{ $comment->id }}">
                                            @csrf
                                            @method('DELETE')

                                            <span
                                                class="text-uppercase text-muted xsmall">{{ date('M d, Y', strtotime($comment->created_at)) }}</span>

                                            @if (Auth::user()->id === $comment->user->id)
                                                &middot;
                                                <button type="submit"
                                                    class="border-0 bg-transparent text-danger p-0 xsmall">Delete</button>
                                            @endif
                                        </form>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
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
                        const commentList = form.closest('.mt-4').querySelector('.list-group');
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

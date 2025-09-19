<div class="mt-3">
    {{-- Show all comments here --}}
    @if ($post->comments->isNotEmpty())
        <hr>
        <ul class="list-group">
            @foreach ($post->comments->take(3) as $comment)
                <li class="list-group-item border-0 p-2 mb-2 rounded shadow-sm" 
                    style="background: #f3f0ee;">
                    
                    {{-- Comment Author --}}
                    <a href="{{ route('profile.show', $comment->user->id) }}" 
                       class="fw-bold text-decoration-none" 
                       style="color: #4f463c;">
                        {{ $comment->user->name }}
                    </a>
                    
                    {{-- Comment Body --}}
                    <p class="d-inline fw-light text-dark">{{ $comment->body }}</p>

                    {{-- Metadata + Actions --}}
                    <div class="d-flex justify-content-between align-items-center mt-1">
                        <span class="text-uppercase small" style="color: #B0A695;">
                            {{ date('M d, Y', strtotime($comment->created_at)) }}
                        </span>

                        {{-- If the Auth user is the owner , show delete btn --}}
                        @if (Auth::user()->id === $comment->user->id)
                            <form action="{{ route('comment.destroy', $comment->id) }}" method="post" class="m-0">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="border-0 bg-transparent small fw-bold" 
                                        style="color: #EB5E55;">
                                    Delete
                                </button>
                            </form>
                        @endif
                    </div>
                </li>
            @endforeach

            {{-- View All Link --}}
            @if ($post->comments->count() > 3)
                <li class="list-group-item border-0 px-0 pt-0">
                    <a href="{{ route('post.show', $post->id) }}" 
                       class="text-decoration-none small fw-bold" 
                       style="color: #776B5D;">
                        View all {{ $post->comments->count() }} comments
                    </a>
                </li>
            @endif
        </ul>
    @endif

    {{-- Add Comment --}}
    <form action="{{ route('comment.store', $post->id) }}" method="post" class="mt-2">
        @csrf
        <div class="input-group">
            <textarea name="comment_body{{ $post->id }}" 
                      cols="30" rows="1" 
                      class="form-control form-control-sm" 
                      placeholder="Add a comment..."
                      style="border: 1px solid #B0A695;">{{ old('comment_body' . $post->id)}}</textarea>
            <button type="submit" 
                    class="btn btn-sm" 
                    style="background:#776B5D; color:#fff;">
                <i class="fa-regular fa-paper-plane"></i>
            </button>
        </div>

        {{-- Error --}}
        @error('comment_body' . $post->id)
            <div class="text-danger small">{{ $message }}</div>
        @enderror
    </form>
</div>

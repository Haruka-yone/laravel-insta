<div class="modal fade" id="delete-post-{{ $post->id }}">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 15px; overflow: hidden;">
            
            <!-- Modal Header -->
            <div class="modal-header" style="background-color:#776B5D; color: #F3EEEA; border-bottom: none;">
                <h3 class="h5 modal-title mb-0">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i> Delete Post
                </h3>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            
            <!-- Modal Body -->
            <div class="modal-body text-center" style="background-color: #F3EEEA;">
                <p class="fw-bold text-dark">Are you sure you want to delete this post?</p>
                
                <div class="mt-3">
                    <img src="{{ $post->image }}" 
                         alt="post id {{ $post->id }}" 
                         class="rounded shadow-sm mb-2 image-lg" 
                         style="border: 3px solid #B0A695;">
                    <p class="mt-1 text-muted fst-italic">{{ $post->description }}</p>
                </div>
            </div>
            
            <!-- Modal Footer -->
            <div class="modal-footer justify-content-between" style="background-color: #EBE3D5; border-top: none;">
                <button type="button" class="btn btn-sm" 
                        style="background-color:#B0A695; color:#fff; border-radius: 8px;" 
                        data-bs-dismiss="modal">
                    Cancel
                </button>
                <form action="{{ route('post.delete', $post->id) }}" method="post" class="m-0">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm" 
                            style="background-color:#C0392B; color:#fff; border-radius: 8px;">
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

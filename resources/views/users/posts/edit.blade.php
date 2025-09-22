@extends('layouts.app')

@section('title', 'Edit Post')

@section('content')
    <style>
        .card {
            max-width: 900px;
            margin: auto;
        }
    </style>

    <div class="row justify-content-center fade-in">
        <div class="col-9">
            <div class="card shadow border-0 rounded-4 p-4" style="background-color:#F3EEEA;">
                <h4 class="fw-bold mb-4" style="color:#776B5D;">
                    <i class="fa-solid fa-pen-to-square me-2"></i> Edit Post
                </h4>

                <form action="{{ route('post.update', $post->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    {{-- Category --}}
                    <div class="mb-4">
                        <label for="category" class="form-label fw-bold" style="color:#776B5D;">
                            Category <span class="text-muted fw-normal small">(choose up to 3)</span>
                        </label>
                        <div>
                            @foreach ($all_categories as $category)
                                <div class="form-check form-check-inline">
                                    <input type="checkbox" name="category[]" id="cat-{{ $category->id }}"
                                        value="{{ $category->id }}" class="form-check-input accent-color"
                                        style="border-color:#776B5D"
                                        {{ in_array($category->id, $selected_categories) ? 'checked' : '' }}>
                                    <label for="cat-{{ $category->id }}"
                                        class="form-check-label">{{ $category->name }}</label>
                                </div>
                            @endforeach
                        </div>
                        @error('category')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div class="mb-4">
                        <label for="description" class="form-label fw-bold" style="color:#776B5D;">Description</label>
                        <textarea name="description" id="description" rows="3" class="form-control shadow-sm border-0"
                            placeholder="What's on your mind?">{{ old('description', $post->description) }}</textarea>
                        @error('description')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Image --}}
                    <div class="mb-4">
                        <label class="form-label fw-bold" style="color:#776B5D;">Current Images</label>
                        <div class="d-flex flex-wrap gap-3">
                            @foreach ($post->images as $image)
                                <div class="position-relative" style="width:200px; height:200px;">
                                    <img src="{{ $image->image }}" alt="Post Image" class="rounded shadow-sm"
                                        style="width:100%; height:100%; object-fit:cover;">

                                    <form action="{{ route('post-images.destroy', $image->id) }}" method="POST"
                                        class="position-absolute" style="top: 8px; right: 8px;">

                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="btn btn-sm btn-danger rounded-circle p-2 d-flex align-items-center justify-content-center"
                                            style="width:30px; height:30px;" title="Delete Image">
                                            <i class="fa-solid fa-trash fa-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>

                        {{-- Upload New Images --}}
                        <label for="images" class="form-label fw-bold" style="color:#776B5D;">Add New Images</label>
                        <input type="file" name="images[]" id="images" class="form-control shadow-sm border-0"
                            accept="image/*" multiple aria-describedby="image-info">

                        <div id="image-info" class="form-text text-muted small">
                            Acceptable formats: jpeg, png, gif. <br>
                            Max file size: 1048kb. <br>
                            You can select multiple files.
                        </div>
                        @error('images.*')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror

                        {{-- New Preview --}}
                        <div id="preview" class="mt-3"></div>
                    </div>

                    {{-- Save Button --}}
                    <div class="text-end">
                        <button type="submit" class="btn px-5 py-2 rounded-pill fancy-btn shadow-sm"
                            style="background-color:#B0A695; color:white; font-weight:bold; transition:0.3s;">
                            <i class="fa-regular fa-floppy-disk me-1"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Preview new image before upload
        document.getElementById('image').addEventListener('change', function(e) {
            let preview = document.getElementById('preview');
            preview.innerHTML = ""; // reset preview
            let file = e.target.files[0];

            if (file) {
                let reader = new FileReader();
                reader.onload = function(event) {
                    let img = document.createElement("img");
                    img.src = event.target.result;
                    img.classList.add("rounded-3", "shadow-sm", "fade-in");
                    img.style.width = "200px";
                    img.style.height = "200px";
                    img.style.objectFit = "cover";
                    img.style.transition = "transform 0.3s ease";
                    img.onmouseover = () => img.style.transform = "scale(1.05)";
                    img.onmouseout = () => img.style.transform = "scale(1)";
                    preview.appendChild(img);
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
@endpush

@push('styles')
    <style>
        .accent-color:checked {
            background-color: #B0A695 !important;
            border-color: #B0A695 !important;
        }

        .fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .fancy-btn:hover {
            background-color: #776B5D !important;
        }
    </style>
@endpush

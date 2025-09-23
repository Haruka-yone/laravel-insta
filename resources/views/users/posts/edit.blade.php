@extends('layouts.app')

@section('title', 'Edit Post')

@section('content')
    <div class="row justify-content-center fade-in">
        <div class="col-9">
            <div class="card shadow border-0 rounded-4 p-4" style="background-color:#F3EEEA;">
                <h4 class="fw-bold mb-4" style="color:#776B5D;">
                    <i class="fa-solid fa-pen-to-square me-2"></i> Edit Post
                </h4>

                {{-- Single Update Form --}}
                <form action="{{ route('post.update', $post->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    {{-- Category --}}
                    <div class="mb-4">
                        <label class="form-label fw-bold" style="color:#776B5D;">
                            Category <span class="text-muted fw-normal small">(choose up to 3)</span>
                        </label>
                        <div>
                            @foreach ($all_categories as $category)
                                <div class="form-check form-check-inline">
                                    <input type="checkbox" name="category[]" id="cat-{{ $category->id }}"
                                           value="{{ $category->id }}" class="form-check-input accent-color"
                                           style="border-color:#776B5D"
                                           {{ in_array($category->id, $selected_categories) ? 'checked' : '' }}>
                                    <label for="cat-{{ $category->id }}" class="form-check-label">
                                        {{ $category->name }}
                                    </label>
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
                        <textarea name="description" id="description" rows="3"
                                  class="form-control shadow-sm border-0"
                                  placeholder="What's on your mind?">{{ old('description', $post->description) }}</textarea>
                        @error('description')
                        <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Current Images --}}
                    <div class="mb-4">
                        <label class="form-label fw-bold" style="color:#776B5D;">Current Images</label>
                        <div class="d-flex flex-wrap gap-3">
                            @foreach ($post->images as $image)
                                <div class="image-wrapper position-relative" style="width:200px; height:200px;">
                                    <img src="{{ $image->image }}" alt="Post Image" class="rounded shadow-sm"
                                         style="width:100%; height:100%; object-fit:cover;">

                                    {{-- Delete Checkbox with Trash Icon --}}
                                    <label class="delete-icon position-absolute" style="top: 8px; right: 8px; cursor:pointer;">
                                        <input type="checkbox" name="delete_images[]" value="{{ $image->id }}"
                                               class="d-none delete-checkbox">
                                        <span
                                            class="btn btn-sm btn-danger rounded-circle p-2 d-flex align-items-center justify-content-center"
                                            style="width:30px; height:30px;">
                                            <i class="fa-solid fa-trash fa-xs"></i>
                                        </span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Add New Images --}}
                    <div class="mb-4">
                        <label class="form-label fw-bold" style="color:#776B5D;">Add New Images</label>

                        <div id="image-wrapper">
                            <div class="input-group mb-2">
                                <input type="file" name="images[]" class="form-control shadow-sm border-0"
                                       accept="image/*">
                            </div>
                        </div>

                        <button type="button" id="add-more" class="btn btn-sm btn-outline-secondary">+ Add More</button>

                        <div id="preview" class="mt-3 d-flex flex-wrap gap-2"></div>

                        @error('images.*')
                        <div class="text-danger small">{{ $message }}</div>
                        @enderror
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
        document.addEventListener("DOMContentLoaded", function () {
            // Add more file inputs
            document.getElementById('add-more').addEventListener('click', function () {
                let wrapper = document.getElementById('image-wrapper');
                let div = document.createElement('div');
                div.classList.add('input-group', 'mb-2');
                div.innerHTML = `
                    <input type="file" name="images[]" class="form-control shadow-sm border-0" accept="image/*">
                `;
                wrapper.appendChild(div);
            });

            // Preview newly selected images
            document.addEventListener('change', function (e) {
                if (e.target.name === "images[]") {
                    let preview = document.getElementById('preview');
                    preview.innerHTML = ""; // reset preview
                    let files = document.querySelectorAll('input[name="images[]"]');
                    files.forEach(fileInput => {
                        if (fileInput.files[0]) {
                            let reader = new FileReader();
                            reader.onload = function (event) {
                                let img = document.createElement("img");
                                img.src = event.target.result;
                                img.classList.add("rounded-3", "shadow-sm");
                                img.style.width = "120px";
                                img.style.height = "120px";
                                img.style.objectFit = "cover";
                                preview.appendChild(img);
                            }
                            reader.readAsDataURL(fileInput.files[0]);
                        }
                    });
                }
            });

            // ✅ Remove current image immediately when delete checkbox is clicked
            document.querySelectorAll('.delete-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', function () {
                    if (this.checked) {
                        const imageWrapper = this.closest('.image-wrapper');
                        if (imageWrapper) {
                            imageWrapper.style.transition = "opacity 0.3s ease";
                            imageWrapper.style.opacity = "0";
                            setTimeout(() => {
                                imageWrapper.style.display = "none";
                            }, 300);
                        }
                    }
                });
            });
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

        /* Highlight trash icon when checked */
        .delete-checkbox:checked + span {
            background-color: #c82333 !important;
            opacity: 0.8;
        }
    </style>
@endpush

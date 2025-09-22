@extends('layouts.app')

@section('title', 'Create Post')

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
                    <i class="fa-regular fa-square-plus me-2"></i> Create a New Post
                </h4>

                {{-- Form --}}
                <form action="{{ route('post.store') }}" method="post" enctype="multipart/form-data">
                    @csrf

                    {{-- Category --}}
                    <div class="mb-4">
                        <label for="category" class="form-label fw-bold" style="color:#776B5D;">
                            Category
                            <span class="text-muted fw-normal small">(choose up to 3)</span>
                        </label>
                        <div>
                            @foreach ($all_categories as $category)
                                <div class="form-check form-check-inline">
                                    <input type="checkbox" name="category[]" id="cat-{{ $category->id }}"
                                        value="{{ $category->id }}" class="form-check-input accent-color"
                                        style="border-color: #776B5D">
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
                            placeholder="What's on your mind?">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Multiple Images --}}
                    <div class="mb-4">
                        <label class="form-label fw-bold" style="color:#776B5D;">Upload Images</label>

                        <div id="image-wrapper">
                            <div class="input-group mb-2">
                                <input type="file" name="images[]" class="form-control shadow-sm border-0"
                                    accept="image/*">
                                {{-- <input type="text" name="captions[]" class="form-control shadow-sm border-0"
                                    placeholder="Enter caption (optional)"> --}}
                            </div>
                        </div>

                        <button type="button" id="add-more" class="btn btn-sm btn-outline-secondary">+ Add More</button>

                        <div id="preview" class="mt-3 d-flex flex-wrap gap-2"></div>

                        @error('images')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>


                    <div class="text-end">
                        <button type="submit" class="btn px-5 py-2 rounded-pill fancy-btn shadow-sm"
                            style="background-color:#B0A695; color:white; font-weight:bold; transition:0.3s;">
                            <i class="fa-regular fa-paper-plane me-1"></i> Post
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Add more image+caption fields
    document.getElementById('add-more').addEventListener('click', function() {
        let wrapper = document.getElementById('image-wrapper');
        let div = document.createElement('div');
        div.classList.add('input-group', 'mb-2');
        div.innerHTML = `
            <input type="file" name="images[]" class="form-control shadow-sm border-0" accept="image/*">
        `;
        wrapper.appendChild(div);

        // Remove input group
        div.querySelector('.remove-btn').addEventListener('click', function() {
            div.remove();
        });
    });

    // Preview multiple images
    document.addEventListener('change', function(e) {
        if (e.target.name === "images[]") {
            let preview = document.getElementById('preview');
            preview.innerHTML = ""; // reset preview
            let files = document.querySelectorAll('input[name="images[]"]');
            files.forEach(fileInput => {
                if (fileInput.files[0]) {
                    let reader = new FileReader();
                    reader.onload = function(event) {
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

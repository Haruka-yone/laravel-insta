@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
    <style>
        /* keep the card width same as create.blade */
        .card {
            max-width: 900px;
            margin: auto;
        }
    </style>

    <div class="row justify-content-center fade-in">
        <div class="col-9">
            <div class="card shadow border-0 rounded-4 p-4" style="background-color:#F3EEEA;">
                <h4 class="fw-bold mb-4" style="color:#776B5D;">
                    <i class="fa-solid fa-user-pen me-2"></i> Update Profile
                </h4>

                <form action="{{ route('profile.update', $user->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    {{-- Avatar --}}
                    <div class="mb-4">
                        @if ($user->avatar)
                            <img id="avatar-preview-existing" src="{{ $user->avatar }}" 
                                 alt="{{ $user->name }}" 
                                 class="rounded-circle shadow-sm mb-3"
                                 style="width:120px; height:120px; object-fit:cover; border:4px solid #B0A695;">
                        @else
                            <i id="avatar-preview-existing" class="fa-solid fa-circle-user mb-3" 
                               style="font-size:120px; color:#B0A695;"></i>
                        @endif

                        <div class="d-flex flex-column">
                            <input type="file" name="avatar" id="avatar" 
                                   class="form-control shadow-sm border-0 w-75" accept="image/*">
                            <div class="form-text text-muted small mt-1">
                                Acceptable formats: jpeg, jpg, png, gif <br>
                                Max file size: 1048kb
                            </div>
                            @error('avatar')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                            {{-- New preview will appear here --}}
                            <div id="avatar-preview-wrapper" class="mt-3"></div>
                        </div>
                    </div>

                    {{-- Name --}}
                    <div class="mb-4">
                        <label for="name" class="form-label fw-bold" style="color:#776B5D;">Name</label>
                        <input type="text" name="name" id="name" 
                               class="form-control shadow-sm border-0" 
                               value="{{ old('name', $user->name) }}" autofocus>
                        @error('name')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="mb-4">
                        <label for="email" class="form-label fw-bold" style="color:#776B5D;">E-Mail Address</label>
                        <input type="email" name="email" id="email" 
                               class="form-control shadow-sm border-0" 
                               value="{{ old('email', $user->email) }}">
                        @error('email')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Introduction --}}
                    <div class="mb-4">
                        <label for="introduction" class="form-label fw-bold" style="color:#776B5D;">Introduction</label>
                        <textarea name="introduction" id="introduction" rows="5" 
                                  class="form-control shadow-sm border-0" 
                                  placeholder="Describe yourself">{{ old('introduction', $user->introduction) }}</textarea>
                        @error('introduction')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Save Button (same as Create) --}}
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
        // Avatar preview (single image) — mirrors the create.blade image preview behavior
        document.getElementById('avatar').addEventListener('change', function(e) {
            const wrapper = document.getElementById('avatar-preview-wrapper');
            const existing = document.getElementById('avatar-preview-existing');
            wrapper.innerHTML = ""; // reset preview

            const file = e.target.files[0];
            if (!file) return;

            // optional: file size check (1048 KB)
            const maxKB = 1048;
            if (file.size / 1024 > maxKB) {
                alert("Avatar must be less than " + maxKB + " KB.");
                e.target.value = "";
                return;
            }

            const reader = new FileReader();
            reader.onload = function(event) {
                // hide existing preview (img or icon)
                if (existing) existing.style.display = 'none';

                const img = document.createElement('img');
                img.src = event.target.result;
                img.classList.add('rounded-3', 'shadow-sm', 'fade-in');
                img.style.width = '120px';
                img.style.height = '120px';
                img.style.objectFit = 'cover';
                img.style.transition = 'transform 0.3s ease';
                img.onmouseover = () => img.style.transform = 'scale(1.05)';
                img.onmouseout = () => img.style.transform = 'scale(1)';
                wrapper.appendChild(img);
            }
            reader.readAsDataURL(file);
        });
    </script>
@endpush

@push('styles')
    <style>
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

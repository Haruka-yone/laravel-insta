@extends('layouts.app')

@section('title', 'Create Story')

@section('content')
    <form action="{{ route('story.store') }}" method="post" enctype="multipart/form-data">
        @csrf

        <div class="mb-4">
            <label for="image" class="form-label fw-bold">Image</label>
            <input type="file" name="image" id="image" class="form-control" aria-describedat="image-info">
            <div class="form-text" id="image-info">
                The acceptable formats are jpeg, png, and gif only. <br>
                Max file size is 1048kB.
            </div>

            {{-- Error --}}
            @error('image')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label for="description" class="form-label fw-bold">Description</label>
            <textarea name="description" id="description" cols="" rows="3" class="form-control" placeholder="What's on your mind">{{ old('description') }}</textarea>

            {{-- Error --}}
            @error('description')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary px-5">Post</button>

    </form>
@endsection
@extends('layouts.app')

@section('title', 'Explore Category')

@section('content')
    <h3>Search Posts by Category</h3>

    <form action="" method="post" class="d-flex mt-3">
        <select name="category" class="form-select w-25">
            <option value=""> Select Category </option>
            @foreach ($all_categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-primary ms-2">Apply</button>
    </form>

    <div class="row">
        <div class="col-3 mb-3">
            <img src="" alt="" class="img-fluid rounded">
        </div>
    </div>
@endsection
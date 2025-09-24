@extends('layouts.app')

@section('title', 'Admin Categories')

@section('content')

    <form action="{{ route('admin.categories.store') }}" method="post" class="d-flex mb-3">
        @csrf
        <input type="text" name="name" class="form-control w-50" placeholder="Add a category">
        <button type="submit" class="btn btn-primary ms-2"><i class="fa-solid fa-plus me-1"></i> Add</button>
    </form>

    <table class="table table-hover align-middle bg-white border text-secondary w-75">
        <thead class="small table-warning text-secondary text-center">
            <tr>
                <th>#</th>
                <th>NAME</th>
                <th>COUNT</th>
                <th>LAST UPDATED</th>
                <th></th>
            </tr>
        </thead>
        <tbody class="text-center">
            @foreach ($all_categories as $category)
                <tr>
                    <td>{{ $category->id }}</td>
                    <td>{{ $category->name }}</td>
                    <td>{{ $category->categoryPost->count() }}</td>
                    <td>{{ $category->updated_at }}</td>
                    <td>
                        <div class="row">
                            <div class="col">
                                <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#edit-category-{{ $category->id }}">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#delete-category-{{ $category->id }}">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </div>
                        </div>
                        @include('admin.categories.modals.status')
                    </td>
                </tr>
            @endforeach
                <tr>
                    <td></td>
                    <td>
                        Uncategorized
                        <p class="text-muted small">Hidden posts are not included.</p>
                    </td>
                    <td>{{ $uncategorized_count }}</td>
                    <td></td>
                    <td></td>
                </tr>
        </tbody>
    </table>
@endsection
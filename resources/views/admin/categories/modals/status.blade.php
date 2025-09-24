<div class="modal fade" id="edit-category-{{ $category->id }}">
    <div class="modal-dialog">
        <div class="modal-content border-warning">
            <div class="modal-header border-warning">
                <h3 class="h5 modal-title text-warning">
                    <i class="fa-solid fa-pen-to-square"></i> Edit Category
                </h3>
            </div>
            <form action="{{ route('admin.categories.update', $category->id) }}" method="post">
                @csrf
                @method('PATCH')

                <div class="modal-body">
                    <input type="text" name="name" id="name" value="{{ old('name',$category->name) }}" class="form-control">
                    @error('name')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-warning btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning btn-sm">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="delete-category-{{ $category->id }}">
    <div class="modal-dialog">
        <div class="modal-content border-danger">
            <div class="modal-header border-danger">
                <h3 class="h5 modal-title text-danger">
                    <i class="fa-solid fa-trash-can"></i> Delete Category
                </h3>
            </div>
            <div class="modal-body text-start">
                Are you sure you want to delete <span class="fw-bold">{{ $category->name }}</span> category ?

                <p class="mt-3 text-muted">This action will affect all the posts under this category. Posts without a caterory will fall under Uncategorized.</p>
            </div>
            <div class="modal-footer border-0">
                    <form action="{{ route('admin.categories.destroy', $category->id) }}" method="post">
                    @csrf
                    @method('DELETE')

                    <button type="button" class="btn btn-outline-danger btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
               </form>
            </div>
        </div>
    </div>
</div>
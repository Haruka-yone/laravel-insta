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
                                <button 
                                    class="btn btn-md btn-outline-warning edit-btn p-2" data-id="{{ $category->id }}"
                                    data-name="{{ $category->name }}"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editCategoryModal">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                <button class="btn btn-md btn-outline-danger p-2" data-bs-toggle="modal" data-bs-target="#delete-category-{{ $category->id }}">
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
    <div class="modal fade" id="editCategoryModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content border-warning">
                <div class="modal-header border-warning">
                    <h3 class="h5 modal-title text-warning">
                        <i class="fa-solid fa-pen-to-square"></i> Edit Category
                    </h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="editCategoryId">
                    <input type="text" id="editCategoryName" class="form-control">
                    <div id="editError" class="text-danger small mt-1"></div>
                </div>

                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-warning btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button id="updateCategoryBtn" type="button" class="btn btn-warning btn-sm">Update</button>
                </div>
            </div>
        </div>
    </div>
@endsection

<script>
document.addEventListener('DOMContentLoaded', function () {
    let editId = null;

    // 編集ボタンをクリックしたとき → モーダルに値をセット
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            editId = this.dataset.id;
            document.getElementById('editCategoryId').value = editId;
            document.getElementById('editCategoryName').value = this.dataset.name;
            document.getElementById('editError').innerText = '';
        });
    });

    // Update ボタンを押したとき
    document.getElementById('updateCategoryBtn').addEventListener('click', function () {
        const name = document.getElementById('editCategoryName').value.trim();
        const errorDiv = document.getElementById('editError');

        // ✅ 50文字制限のクライアントサイドチェック
        if (name.length === 0) {
            errorDiv.innerText = "Category name is required.";
            return;
        }
        if (name.length > 50) {
            errorDiv.innerText = "The category name must not exceed 50 characters.";
            return;
        }

        // サーバーへ送信
        fetch(`/admin/categories/${editId}/update`, {   // ← ルートに合わせて修正済み
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json', // ← 追加
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ name: name })
        })
        .then(async response => {
            if (response.status === 422) {
                errorDiv.innerText = "The category name must not exceed 50 characters.";
                return;
            }

            if (!response.ok) {
                errorDiv.innerText = "Update failed!";
                return;
            }

            // JSON として安全に読み込む
            let data = {};
            try {
                data = await response.json();
            } catch {
                errorDiv.innerText = "Unexpected response format.";
                return;
            }

            if (data.success) {
                // モーダルを閉じる
                const modalEl = document.getElementById('editCategoryModal');
                const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
                modal.hide();

                // DOM 更新（リロード不要）
                // document.querySelector(`.edit-btn[data-id="${editId}"]`)
                //     .closest('tr')
                //     .querySelector('td:nth-child(2)').innerText = data.name ?? name;
                const row = document.querySelector(`.edit-btn[data-id="${editId}"]`).closest('tr');
                row.querySelector('td:nth-child(2)').innerText = data.name;
                row.querySelector('td:nth-child(4)').innerText = data.updated_at;
            }
        })
        .catch(() => {
            // errorDiv.innerText = "Network error!";
            alert("something went wrong");
        });
    });
});
</script>


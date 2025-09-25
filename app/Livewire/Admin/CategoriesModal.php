<?php 

// namespace App\Livewire\Admin;

// use App\Models\Category;
// use Livewire\Component;

// class CategoriesModal extends Component
// {
//     public $categoryId;
//     public $name;
//     protected $rules = [
//         'name' => 'required|string|max:50|unique:categories,name',
//     ];
//     protected $messages = [
//         'name.required' => 'Category name is required.',
//         'name.unique'   => 'This category name is already taken.',
//     ];
//     public function mount($category)
//     {
//         $this->categoryId = $category->id;
//         $this->name = $category->name;
//     }
//     public function update()
//     {
//         $this->validate();

//         $category = Category::find($this->categoryId);
//         $category->name = $this->name;
//         $category->save();

//         session()->flash('success', 'Category updated successfully!');

//         // モーダルを閉じる
//         $this->dispatch('close-modal', id: $this->categoryId);
//     }

//     public function render()
//     {
//         return view('livewire.admin.categories-modal');
//     }
    
// }

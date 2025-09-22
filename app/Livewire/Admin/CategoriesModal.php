<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use Livewire\Component;

class CategoriesModal extends Component
{
    public $name;
    public $categoryId;

    protected $rules = [
        'name' => 'required|min:3|max:50',
    ];

    protected $listeners = [
        'editCategory' => 'edit' 
    ];

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $this->categoryId = $id;
        $this->name = $category->name;

        $this->dispatchBrowserEvent('open-modal', ['id' => 'edit-category']);
    }

    public function update()
    {
        $this->validate();
        Category::find($this->categoryId)->update(['name' => $this->name]);

        $this->dispatchBrowserEvent('close-modal', ['id' => 'edit-category']);
        session()->flash('success', 'Category updated successfully!');
    }


    public function render()
    {
        return view('livewire.admin.categories-modal');
    }
}

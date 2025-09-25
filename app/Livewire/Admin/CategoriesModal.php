<!-- <?php 

// namespace App\Livewire\Admin;

// use App\Models\Category;
// use Livewire\Component;

// class CategoriesModal extends Component
// {
//     public $categoryId;
//     public $name;

//     protected $rules = [
//         'name' => 'required|max:50',
//     ];

//     protected $listeners = [
//         'open-modal' => 'loadCategory'
//     ];

//     // モーダルを開くときに呼び出される
//     public function loadCategory($id)
//     {
//         $category = Category::findOrFail($id);
//         $this->categoryId = $category->id;
//         $this->name = $category->name;

//         // JSにモーダル表示を伝える
//         $this->dispatch('show-edit-modal');
//     }

//     public function update()
//     {
//         try {
//             $this->validate();

//             $category = Category::findOrFail($this->categoryId);
//             $category->name = $this->name;
//             $category->save();

//             // 成功したらモーダルを閉じる
//             $this->dispatch('hide-edit-modal');

//             // 入力フィールドをリセット
//             $this->reset(['categoryId', 'name']);

//         } catch (\Illuminate\Validation\ValidationException $e) {
//             // バリデーションエラー時は閉じない
//             throw $e;
//         }
//     }

//     public function render()
//     {
//         return view('livewire.admin.categories-modal');
//     }
    
// }

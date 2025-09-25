<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    private $category;
    private $post;

    public function __construct(Category $category, Post $post){
        $this->category = $category;
        $this->post = $post;
    }

    public function index(){
        $all_categories = $this->category->get();

        $all_posts = $this->post->all();
        $uncategorized_count = 0;

        foreach($all_posts as $post){
            if($post->categoryPost->count() == 0){
                $uncategorized_count++;
            }
        }
        
        return view('admin.categories.index')
                ->with('all_categories', $all_categories)
                ->with('uncategorized_count', $uncategorized_count);
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'required|max:50'
        ]);

        $this->category->name = $request->name;
        $this->category->save();

        return redirect()->back();
    }

    // public function update(Request $request, $id){
    //     $request->validate([
    //         'name'  =>  'required|max:50'
    //     ]);

    //     $category = $this->category->findOrFail($id);
    //     $category->name = $request->name;
    //     $category->save();

    //     return redirect()->back();
    // }

    public function update(Request $request, $id){
        $request->validate([
            'name'  =>  'required|string|max:50'
        ]);

        $category = $this->category->findOrFail($id);
        $category->name = $request->name;
        $category->save();

        return response()->json([
            'success'     => true, 
            'message'     => 'Category updated',
            'name'        => $category->name,
            'updated_at'  => $category->updated_at->toDateTimeString()
        ]);
    }

    public function destroy($id){
        $this->category->destroy($id);

        return redirect()->back();
    }

}

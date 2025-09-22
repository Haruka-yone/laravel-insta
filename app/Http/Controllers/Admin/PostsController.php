<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;

class PostsController extends Controller
{
    private $post;
    private $category;

    public function __construct(Post $post, Category $category){
        
        $this->post = $post;
        $this->category = $category;
    }

    // public function index(){
    //     $all_posts = $this->post->withTrashed()->latest()->paginate(5);
    //     //後でwithTrash入れる

    //     return view('admin.posts.index')->with('all_posts', $all_posts);
    // }

    public function index(Request $request){
        $keyword = $request->search;
        $selectedCategories = $request->input('categories',[]);

        $query = $this->post->query();

        if(!empty($keyword)){
            $query->whereHas('categoryPost.category', function ($q) use($keyword){
            $q->where('name', 'like', '%'. $keyword.'%');
            });
        }

        if(!empty($selectedCategories)){
            $query->whereHas('categoryPost', function($q) use($selectedCategories){
                $q->wherIn('category_id', $selectedCategories);
            });
        }
        
        $all_posts = $query->withTrashed()->latest()->paginate(5);
        
        $all_categories = $this->category->all();

        return view('admin.posts.index')->with('all_posts', $all_posts)->with('all_categories',$all_categories);
    }

    public function hide($id){
        $this->post->destroy($id);
        return redirect()->back();
    }

    public function unhide($id){
        $this->post->onlyTrashed()->findOrFail($id)->restore();

        return redirect()->back();
    }

    public function search(Request $request){
        $keyword = $request->search;

        $posts = $this->post->whereHas('categoryPost.category', function ($query) use($keyword){
            $query->where('name', 'like', '%'. $keyword.'%');
        })
        ->get();

        return view('admin.posts.index')->with('$posts', $posts)->with('keyword', $keyword);
    }
}

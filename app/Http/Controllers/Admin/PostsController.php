<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostsController extends Controller
{
    private $post;

    public function __construct(Post $post){
        $this->post = $post;
    }

    // public function index(){
    //     $all_posts = $this->post->withTrashed()->latest()->paginate(5);
    //     //後でwithTrash入れる

    //     return view('admin.posts.index')->with('all_posts', $all_posts);
    // }

    public function index(Request $request){
        $keyword = $request->search;

        $query = $this->post->query();

        if(!empty($keyword)){
            $query->whereHas('categoryPost.category', function ($q) use($keyword){
            $q->where('name', 'like', '%'. $keyword.'%');
        });
        }

        $all_posts = $query->withTrashed()->latest()->paginate(5);
        //後でwithTrash入れる

        return view('admin.posts.index')->with('all_posts', $all_posts);
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

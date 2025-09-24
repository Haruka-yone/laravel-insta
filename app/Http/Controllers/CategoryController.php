<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    private $category;
    private $post;

    public function __construct(Category $category, Post $post)
    {
        $this->category = $category;
        $this->post = $post;
    }

    public function categorySearch(){
        $all_categories = $this->category->all();
        return view('users.explore')->with('all_categories', $all_categories);
    }

    public function explore(Request $request){

        $selected = $request->category_id;  
        $all_posts = $this->post->latest()->get();
        $all_categories = $this->category->all();

        $posts = [];

        if ($selected === 'all'){
            $posts = $all_posts;
        }
        else{
            foreach ($all_posts as $post) {
                foreach ($post->categoryPost as $categoryPost) {
                    if ($categoryPost->category_id == $selected) {
                        $posts[] = $post;
                        break;
                    }
                }
            }
        }

    return view('users.explore')
        ->with('posts', $posts)
        ->with('all_categories', $all_categories)
        ->with('selected', $selected);
    }

}

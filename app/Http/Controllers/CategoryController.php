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
        
    }

}

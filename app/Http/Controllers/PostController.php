<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use App\Models\Category;


class PostController extends Controller
{
    private $post;
    private $category;

    public function __construct(Post $post, Category $category)
    {
        $this->post = $post;
        $this->category = $category;
    }

    public function create()
    {
        $all_categories = $this->category->all();
        return view('users.posts.create')->with('all_categories', $all_categories);
    }

    public function store(Request $request)
    {
        // Validate
        $request->validate([
            'category' => 'required|array|between:1,3',
            'description' => 'required|min:1|max:1000',
            'images.*' => 'required|mimes:jpeg,jpg,png,gif|max:2048'
        ]);

        // Save post
        $post = new Post();
        $post->user_id = Auth::id();
        $post->description = $request->description;
        $post->save();

        // Save categories
        foreach ($request->category as $category_id) {
            $category_post[] = ['category_id' => $category_id];
        }
        $post->categoryPost()->createMany($category_post);

        // Save multiple images
        if ($request->has('images')) {
            foreach ($request->images as $image) {
                $post->images()->create([
                    'image' => 'data:image/' . $image->extension() . ';base64,' . base64_encode(file_get_contents($image))
                ]);
            }
        }

        return redirect()->route('index');
    }



    public function show($id)
    {
        $post = $this->post->findOrFail($id);

        return view('users.posts.show')
            ->with('post', $post);
    }

    public function edit($id)
    {
        $post = $this->post->with('images')->findOrFail($id);

        if (Auth::id() != $post->user_id) {
            return redirect()->route('index');
        }

        $all_categories = $this->category->all();

        $selected_categories = $post->categoryPost->pluck('category_id')->toArray();

        return view('users.posts.edit')
            ->with('post', $post)
            ->with('all_categories', $all_categories)
            ->with('selected_categories', $selected_categories);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'category' => 'required|array|between:1,3',
            'description' => 'required|min:1|max:1000',
            'images.*' => 'mimes:jpg,png,jpeg,gif|max:2048'
        ]);

        $post = $this->post->with('images')->findOrFail($id);
        $post->description = $request->description;
        $post->save();

        // Update categories
        $post->categoryPost()->delete();
        foreach ($request->category as $category_id) {
            $category_post[] = ['category_id' => $category_id];
        }
        $post->categoryPost()->createMany($category_post);

        // Add new images
        if ($request->has('images')) {
            foreach ($request->images as $image) {
                $post->images()->create([
                    'image' => 'data:image/' . $image->extension() . ';base64,' . base64_encode(file_get_contents($image))
                ]);
            }
        }

        return redirect()->route('post.show', $id)->with('success', 'Post updated successfully.');
    }




    public function destroy($id)
    {
        $this->post->destroy($id);

        return redirect()->route('index');
    }

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Like;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    private $like;

    public function __construct(Like $like){
        $this->like = $like;
    }

    public function store($post_id){
        $this->like->user_id = Auth::user()->id;
        $this->like->post_id = $post_id;
        $this->like->save();

        $post = Post::findOrFail($post_id);

        return response()->json([
            'success' => true,
            'liked' => true,
            'count' => $post->likes()->count(),
        ]);

        // return redirect()->back();
    }

    public function destroy($post_id){
        $this->like->where('user_id', Auth::user()->id)->Where('post_id', $post_id)->delete();

        $post = Post::findOrFail($post_id);

        return response()->json([
            'success' => true,
            'liked' => false,
            'count' => $post->likes()->count(),
        ]);

        // return redirect()->back();
    }
}

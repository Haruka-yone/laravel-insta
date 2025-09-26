<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Comment;

use function PHPUnit\Framework\returnSelf;

class CommentController extends Controller
{
    private $comment;

    public function __construct(Comment $comment){
        $this->comment = $comment;
    }

    public function store(Request $request, $post_id){
        $request->validate([
            'comment_body' .$post_id => 'required|max:150'
        ],
        [
            'comment_body' . $post_id . '.required' => 'You cannot submit an empty comment.',
            'comment_body' . $post_id . '.max' => 'The comment must not have more than 150 characters.'
        ]
        );

        $this->comment->body    = $request->input('comment_body' . $post_id);
        $this->comment->user_id = Auth::user()->id;
        $this->comment->post_id = $post_id;
        $this->comment->save();
        
        // return redirect()->route('post.show', $post_id);

        return response()->json([
            'success' => true,
            'comment' => $this->comment->load('user'),
            'can_delete' => Auth::id() === $this->comment->user_id,
            'delete_url' => route('comment.destroy', $this->comment->id)
        ]);

    }

    public function destroy(Request $request, $id){
        $this->comment->destroy($id);

        if ($request->ajax() || $request->wantsJson()) {
        return response()->json(['success' => true, 'id' => $id]);
        }

        return redirect()->back();
    }

}

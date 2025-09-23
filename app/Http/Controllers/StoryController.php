<?php

namespace App\Http\Controllers;

use App\Models\Story;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class StoryController extends Controller
{
    private $story;

    public function __construct(Story $story)
    {
        $this->story = $story;
    }

    
    public function create()
    {
        return view('users.stories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'description'  => 'min:1|max:500',
            'image'        => 'required|mimes:jpeg,jpg,png,gif|max:1048'
        ]);

        $this->story->user_id = Auth::user()->id;
        $this->story->description = $request->description;
        $this->story->image = 'data:image/' . $request->image->extension() . ';base64,' . base64_encode(file_get_contents($request->image));

        $this->story->expires_at = Carbon::now()->addHours(24);

        $this->story->save();

        return redirect()->route('index');
    }
}

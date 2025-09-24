<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PostImage;

class PostImageController extends Controller
{
     public function destroy($id)
    {
        $image = PostImage::findOrFail($id);
        $image->delete();

        return back()->with('success', 'Image deleted successfully.');
    }
}

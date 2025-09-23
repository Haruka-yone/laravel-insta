<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;
use App\Models\Story;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    private $story;
    private $post;
    private $user;

    public function __construct(Post $post, User $user, Story $story)
    {
        $this->story = $story;
        $this->post = $post;
        $this->user = $user;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // $all_posts = $this->post->latest()->get();
        $home_posts = $this->getHomePosts();
        $all_suggested_users = $this->getSuggestedUsers();
        $suggested_users = array_slice($all_suggested_users, 0, 5);

        $stories = Story::with('user')
            ->where('expires_at', '>', now())
            ->latest()
            ->get();

        // per user grouping
        $storiesByUser = $stories->groupBy('user_id');

        $storiesByUserArray = $storiesByUser->map(function($posts){
            return $posts->map(function($p){
                return [
                    'image'       => $p->image,
                    'description' => $p->description,
                    'user_name'   => $p->user->name,
                    'user_avatar' => $p->user->avatar,
                    'created_at'  => $p->created_at->diffForHumans(),
                ];
            });
        })->map->values()->toArray();

        return view('users.home')
            ->with('home_posts', $home_posts)
            ->with('suggested_users', $suggested_users)
            ->with('all_suggested_users', $all_suggested_users)
            ->with('storiesByUser', $storiesByUser)
            ->with('storiesByUserArray', $storiesByUserArray);
            // ->with('all_posts', $all_posts);
    }

    // Get the posts og the users that logged in user followed
    public function getHomePosts(){
        $all_posts = $this->post->latest()->get();
        $home_posts = []; // in case $home_posts will return empty, this will not return NULL, but empty instead

        foreach($all_posts as $post){
            if($post->user->isFollowed() || $post->user->id === Auth::user()->id){
                $home_posts[] = $post;
            }
        }

        return $home_posts;
    }

    // Get the users that the LOGGED IN (Auth) user is not following
    public function getSuggestedUsers(){
        $all_users = $this->user ->all()->except(Auth::user()->id);
        $suggested_users = []; //empty array to put all suggested users

        foreach($all_users as $user){
            if(!$user->isFollowed()){
                $suggested_users[] = $user;
            }
        }
        return $suggested_users;
    }

    public function search(Request $request){
        $users = $this->user->where('name', 'like', '%'.$request->search.'%')->get();
        return view('users.search')->with('users', $users)->with('search', $request->search);
    }

    
}

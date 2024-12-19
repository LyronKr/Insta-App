<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use App\Models\Post;
use App\Models\User;

class HomeController extends Controller
{
    private $post;
    private $user;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Post $post_model, User $user_model)
    {
        $this->post = $post_model;
        $this->user = $user_model;
        $this->middleware('auth');
    }

    # Get the posts of the users that the Auth user is following
    public function getHomePosts(){
        $all_posts = $this->post->latest()->get();
        $home_posts = [];   // In case the $home_posts at Line 35 is empty, it will not return NULL, but empty array instead

        foreach($all_posts as $post){
            if($post->user->isFollowed() || $post->user->id === Auth::user()->id){
                $home_posts[] = $post;
            }
        }

        return $home_posts;
    }

    public function getSuggestedUsers(){
        $all_users = $this->user->all()->except(Auth::user()->id);
        $suggested_user = [];

        foreach($all_users as $user){
            if(!$user->isFollowed()){
                $suggested_user[] = $user;
            }
        }

        return $suggested_user;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $all_posts      = $this->getHomePosts();
        $suggested_users = $this->getSuggestedUsers();
        return view('users.home')
                ->with('all_posts', $all_posts)
                ->with('suggested_users', $suggested_users);
    }

    public function search(Request $request){
        $users = $this->user->where('name', 'like', '%' . $request->search . '%')->get();
        return view('users.search')
                ->with('users', $users)
                ->with('search', $request->search);
    }

    public function suggested(){
        $suggested_users = $this->getSuggestedUsers();
        return view('users.suggestions')
                ->with('suggested_users', $suggested_users);
    }
}

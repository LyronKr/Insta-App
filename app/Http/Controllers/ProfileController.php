<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

use App\Models\User;
use App\Models\Post;

class ProfileController extends Controller
{
    private $user;
    private $post;

    public function __construct(User $user_model, Post $post_model){
        $this->user = $user_model;
        $this->post = $post_model;
    }

    public function show($id){
        $user = $this->user->findOrFail($id);

        return view('users.profile.show')->with('user', $user);
    }

    public function edit(){
        $user = $this->user->findOrFail(Auth::user()->id);

        return view('users.profile.edit')
                ->with('user', $user);
    }

    public function update(Request $request){
        $request->validate([
            'name'          => 'required|min:1|max:50',
            'email'         => 'required|email|max:50|unique:users,email,' . Auth::user()->id,
            'avatar'        => 'mimes:jpeg,jpg,png,gif|max:1048',
            'introduction'  => 'max:100'
        ]);

        $user = $this->user->findOrFail(Auth::user()->id);
        $user->name     = $request->name;
        $user->email    = $request->email;
        
        // If user uploaded an avatar
        if($request->avatar){
            $user->avatar = 'data:/image' . $request->avatar->extension() . ';base64,' . base64_encode(file_get_contents($request->avatar));
        }

        $user->introduction = $request->introduction;
        $user->save();

        return redirect()->route('profile.show', Auth::user()->id);
    }

    public function followers($id){
        $user = $this->user->findOrFail($id);
        return view('users.profile.followers')->with('user', $user);
    }

    public function following($id){
        $user = $this->user->findOrFail($id);
        return view('users.profile.following')->with('user', $user);
    }

    public function updatePassword(Request $request){
        if(!Hash::check($request->current_password, Auth::user()->password)){
            return redirect()->back()->with('current_password_error', 'That\'s not your current password, try again.')->with('error_password', 'Unable to change your password.');
        }

        if($request->current_password === $request->new_password){
            return redirect()->back()->with('new_password_error', 'New password cannot be the same as your current password. Try again.')->with('error_password', 'Unable to change your password.');
        }

        $request->validate([
            'new_password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()]
        ]);

        $user           = $this->user->findOrFail(Auth::user()->id);
        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->back()->with('success_password', 'Password changed successfully');
    }

    public function showLikedPost($id){
        $all_posts = $this->post->latest()->get();
        $user = $this->user->findOrFail($id);

        // If no post is liked, return empty
        $liked_posts = [];
        foreach($all_posts as $post){
            if($post->isLiked()){
                $liked_posts[] = $post;
            }
        }

        return view('users.profile.liked')
                ->with('liked_posts', $liked_posts)
                ->with('user', $user);
    }
}

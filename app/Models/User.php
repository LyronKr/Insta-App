<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    const ADMIN_ROLE_ID = 1;
    const USER_ROLE_ID  = 2;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    # To get all the post of a user
    public function posts(){
        return $this->hasMany(Post::class)->latest();
    }

    # To get all the followers of a user
    public function followers(){
        return $this->hasMany(Follow::class, 'following_id');
    }

    # To get all the users that the user is following
    public function following(){
        return $this->hasMany(Follow::class, 'follower_id');
    }

    # To check if the user has already followed and return TRUE
    public function isFollowed(){
        return $this->followers()->where('follower_id', Auth::user()->id)->exists();
        // Firstly, get all the followers of the user ($this->followers()). Then, from that list, search for the Auth user from the follower column (where('follower_id', Auth::user()->id))
    }

    # To check if the user is a follower and return TRUE
    public function isFollower(){
        return $this->following()->where('following_id', Auth::user()->id)->exists();
    }

    # To get the likes of a user
    public function likes(){
        return $this->hasMany(Like::class);
    }

    # To get all the liked post of this user
    public function likedPost($post_id){
        return $this->likes()->where('post_id', $post_id)->exists();
    }
}

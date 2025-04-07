<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Follow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class ProfileController extends Controller
{
    public function userProfile(User $user)
    {

        $this->getSharedData($user);

        return view('post-list');
    }


    public function followers(User $user)
    {
        $this->getSharedData($user);
        
        return view('profile-followers', [
            'followers' => $user->followers()->get()
        ]);
    }

    public function following(User $user)
    {
        $this->getSharedData($user);
        return view('profile-following', [
            'following' => $user->following()->get()
        ]);
    }

    private function getSharedData(User $user)
    {
        $isFollowing = 0;
        if (auth()->check()) {
            // Check if user is logged in to not make additional db query.
            $isFollowing = Follow::where([
                ['user_id', '=', auth()->user()->id],
                ['followeduser', '=', $user->id]
            ])->count();
        }
        $avatar = $user->avatar;
        $posts = $user->getPosts()->latest()->paginate(12);
        $numberOfPosts = $posts->count();

        // sharedData is gonna be available in templates when we call $this->getSharedData
        View::share('sharedData', [
            'username' => $user->username,
            'avatar' => $avatar,
            'isFollowing' => $isFollowing,
            'posts' => $posts,
            'numberOfPosts' => $numberOfPosts,
            'followerCount' => $user->followers()->count(),
            'followingCount' => $user->following()->count()
        ]);
    }
}

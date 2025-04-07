<?php

namespace App\Http\Controllers;

use App\Models\Follow;
use App\Models\User;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function createFollow(User $user)
    {
        // Cannot follow yourself.
        if ($user->id === auth()->user()->id) {
            return back()->with('fail', 'You cannot follow yourself!');
        }

        // Cannot follow someone you're already following.
        $checkExistence = Follow::where(
            [
                ['user_id', '=', auth()->user()->id],
                ['followeduser', '=', $user->id]
            ]
        )->count();

        if ($checkExistence) {
            return back()->with('fail', 'You already follow the user!');
        }

        $newFollow = new Follow();
        $newFollow->user_id = auth()->user()->id;
        $newFollow->followeduser = $user->id;

        $newFollow->save();

        return back()->with('success', 'User Unfollwed');
    }

    public function removeFollow(User $user)
    {
        Follow::where([['user_id', '=', auth()->user()->id], ['followeduser', '=', $user->id]])->delete();

        return back()->with('success', 'User Unfollwed.');
    }
}

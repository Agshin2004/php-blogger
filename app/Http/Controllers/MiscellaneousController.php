<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Bookmark;
use App\Events\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class MiscellaneousController extends Controller
{
    public function home()
    {
        $isLoggedIn = auth()->check();
        if ($isLoggedIn) {
            return view('home-page-feed', [
                'posts' => auth()->user()->feedPosts()->latest()->paginate(12) // 12 posts per page
            ]);
        } else {
            // $postCount = null;
            // if (Cache::has('postCount')) {
            //     $postCount = Cache::get('postCount');
            // }
            // else {
            //     $postCount = Post::count();
            //     Cache::put('postCount', $postCount, 60);
            // }
            //? ===
            $postCount = Cache::remember('postCount', 20, function () {
                // Return value we wanna store in the cache
                return Post::count();
            });

            return view('home-page', [
                'postCount' => $postCount
            ]);
        }
    }

    public function sendMessage(Request $request)
    {
        $data = $request->validate([
            'message' => 'required'
        ]);
        $message = trim($data['message']);

        // toOthers - Broadcast the event to everyone except the current user.
        broadcast(new ChatMessage([
            'selfmessage' => false,
            'username' => auth()->user()->username,
            'message' => strip_tags($message),
            'avatar' => auth()->user()->avatar
        ]))->toOthers();

        return response()->noContent();
    }

    public function bookmarks()
    {
        $posts = auth()->user()->myBookmarks()->latest()->get();

        return view('bookmarks', [
            'posts' => $posts
        ]);
    }

    public function addBookmark($postId)
    {
        $userId = auth()->user()->id;
        if (auth()->user()->myBookmarks->contains($postId)) {
            // operator can be omitted, every consecutive where clause will be 'and'
            Bookmark::where('user_id', $userId)->where('post_id', $postId)->delete();
            
            return redirect()->route('post', ['post' => $postId])
                ->with('success', 'Bookmark Deleted');
        }
        Bookmark::create([
            'user_id' => $userId,
            'post_id' => $postId
        ]);

        return redirect()->route('post', ['post' => $postId])
            ->with('success', 'Bookmark added!');
    }
}

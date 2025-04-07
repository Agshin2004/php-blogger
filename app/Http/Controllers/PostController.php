<?php

namespace App\Http\Controllers;

use App\Jobs\SendNewPostEmail;
use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function showCreateForm()
    {
        return view('create-post');
    }

    public function createPost(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'min: 3', 'max: 40'],
            'body' => ['required'],
        ]);

        $title = strip_tags($data['title']);
        $body = strip_tags($data['body']);
        $userId = auth()->id();

        $post = Post::create([
            'title' => $title,
            'body' => $body,
            'user_id' => $userId
        ]);

        // Dispatch a job to its appropriate handler
        dispatch(new SendNewPostEmail([
            'sendTo' => auth()->user()->email,
            'username' => auth()->user()->username,
            'postTitle' => $title
        ]));

        return redirect("/post/{$post->id}")->with('success', "New Post \"{$post->title}\" Was Created.");
    }
    public function singlePost(Post $post)
    {
        $post->body = Str::markdown($post->body);
        return view('single-post', [
            'post' => $post,
        ]);
    }

    public function deletePost(Post $post)
    {
        $post->delete();
        $username = auth()->user()->username;
        return redirect("/profile/{$username}")->with('success', 'Post was deleted successfully.');
    }

    public function showEditForm(Post $post)
    {
        return view('edit-post', [
            'post' => $post
        ]);
    }

    public function updatePost(Post $post, Request $request)
    {
        $postData = $request->validate([
            'title' => 'required',
            'body' => 'required'
        ]);

        $title = strip_tags($postData['title']);
        $body = strip_tags($postData['body']);

        $post->update([
            'title' => $title,
            'body' => $body
        ]);

        return redirect("/post/{$post->id}/")->with('success', "{$post->title} was updated successfully");
    }

    public function search($term)
    {
        $posts = Post::search($term)->get();

        // Load a set of relationships onto the collection.
        $posts->load('getUser:id,username,avatar');
        return $posts;
    }

    public function createNewPostApi(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'min: 3', 'max: 40'],
            'body' => ['required'],
        ]);

        $title = strip_tags($data['title']);
        $body = strip_tags($data['body']);
        $userId = auth()->id();

        $post = Post::create([
            'title' => $title,
            'body' => $body,
            'user_id' => $userId
        ]);

        // // Dispatch a job to its appropriate handler
        // dispatch(new SendNewPostEmail([
        //     'sendTo' => auth()->user()->email,
        //     'username' => auth()->user()->username,
        //     'postTitle' => $title
        // ]));

        return response()->json([
            'message' => "{$post->title} Created"
        ]);
    }

    public function deletePostApi(Post $post)
    {
        $title = $post->title;
        $post->delete();

        response()->json("{$title} Deleted.");
    }
}

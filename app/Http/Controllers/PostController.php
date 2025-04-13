<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostView;
use Cocur\Slugify\Slugify;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Jobs\SendNewPostEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

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
        $slug = (new Slugify())->slugify($title);

        $post = Post::create([
            'title' => $title,
            'body' => $body,
            'user_id' => $userId,
            'slug' => $slug
        ]);

        // Dispatch a job to its appropriate handler
        dispatch(new SendNewPostEmail([
            'sendTo' => auth()->user()->email,
            'username' => auth()->user()->username,
            'postTitle' => $title
        ]));

        return redirect("/post/{$post->slug}")->with('success', "New Post \"{$post->title}\" Was Created.");
    }
    public function singlePost(Post $post, Request $request)
    {
        // Handle post view count
        $user = Auth::user();
        $ip = $request->ip();

        $alreadyViewed = PostView::query()
            ->where('post_id', $post->id)
            ->when($user, fn($query) => $query->where('user_id', $user->id))
            ->when(!$user, fn($q) => $q->where('ip_address', $ip))
            ->exists();

        if (!$alreadyViewed) {
            PostView::create([
                'post_id' => $post->id,
                'user_id' => $user?->id, // If user is logged in add his id
                // Check if user logged in if yes assign null to ip_address otherwise assign ip to ip_address
                'ip_address' => $user ? null : $ip
            ]);
        }

        // Related Posts - Essentially other posts of the user
        $relatedPosts = Auth::user()->getPosts->where('id', '!=', $post->id);

        // Body
        $post->body = Str::markdown($post->body);
        return view('single-post', [
            'post' => $post,
            'viewCount' => $post->views->count(),
            'relatedPosts' => $relatedPosts
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

        return redirect("/post/{$post->slug}/")->with('success', "{$post->title} was updated successfully");
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
        try {
            $title = $post->title;
            $post->delete();

        } catch (\Exception) {
            abort(400, "Post with id ($post->id) not found.");
        }

        return response()->json("{$title} Deleted.");
    }

    public function getSinglePostApi($postId)
    {
        try {
            $post = Post::findOrFail($postId);
            $post->load('getUser:id,username,email');

            return response()->json($post);
        } catch (\Exception $e) {
            abort(404, "Post with id ($postId) not found.");
        }
    }

    public function updatePostApi(Request $request, $postId)
    {
        try {
            $data = $request->validate([
                'title' => ['nullable', 'min: 3'], // Title is optional but if present must be min 3 chars
                'body' => ['required']
            ]);
            $post = Post::findOrFail($postId);
            $post->load('getUser:id,username,email');

            // Since we used $postId instead of post and laravel didn't bind it
            // And 'can:delete,post' - is just wrapper to Gate::authorize()
            // We gotta bind it manually so policies will be checked
            // Gate::authorize('update', $post);
            Gate::authorize('update', $post);
            $post->update($data);

            return response()->json([
                'post' => $post
            ]);

        } catch (\Exception $e) {
            abort(404, $e->getMessage());
        }
    }
}
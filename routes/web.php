<?php

use App\Models\Post;
use App\Events\ChatMessage;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\ProfileController;


//? Admin
Route::get('/admins-only', function () {
    // Testing Gate
    if (Gate::allows('visitAdminPages')) {
        return 'Only admins can visit this page';
    }

    return 'You are not authorized to see this page';
});

Route::get('/', function () { {
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
})->name('home');

Route::middleware(['mustBeLoggedIn'])->group(function () {
    //? Auth Routes.
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/manage-avatar', [AuthController::class, 'showAvatarForm']);
    Route::post('/manage-avatar', [AuthController::class, 'saveAvatar']);

    //? Blog Routes.
    Route::get('/create-post', [PostController::class, 'showCreateForm']);
    Route::post('/create-post', [PostController::class, 'createPost']);

    //? Follow Routes
    Route::post('/follow/{user:username}', [FollowController::class, 'createFollow']);
    Route::post('/unfollow/{user:username}', [FollowController::class, 'removeFollow']);

    //? Chat Routes
    Route::post('/send-message/', function (Request $request) {
        $data = $request->validate([
            'message' => 'required'
        ]);
        $message = trim($data['message']);

        // toOthers - Broadcast the event to everyone except the current user.
        broadcast(new ChatMessage([
            'username' => auth()->user()->username,
            'message' => strip_tags($message),
            'avatar' => auth()->user()->avatar
        ]))->toOthers();

        return response()->noContent();
    });
});

Route::middleware(['guest'])->group(function () {
    //? Auth Routes.
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

//? Public Routes
Route::get('/post/{post}', [PostController::class, 'singlePost']);
Route::get('/profile/{user:username}/followers', [ProfileController::class, 'followers']);
Route::get('/profile/{user:username}/following', [ProfileController::class, 'following']);
// By default, laravel will look up id field in {username} so we can specify lookup row with :
Route::get('/profile/{user:username}', [ProfileController::class, 'userProfile']);
//? Search
Route::get('/search/{term}', [PostController::class, 'search']);


//? Permission Based
Route::delete('delete-post/{post}', [PostController::class, 'deletePost'])->middleware('can:delete,post');
Route::get('/post/{post}/edit', [PostController::class, 'showEditForm'])->middleware('can:update,post');
Route::patch('/post/{post}', [PostController::class, 'updatePost'])->middleware('can:update,post');


//? 404
Route::fallback(fn() => abort(404));
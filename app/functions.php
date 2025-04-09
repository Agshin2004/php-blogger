<?php

namespace App;

use App\Models\User;
use App\Models\Post;
use App\Models\Bookmark;

function inBookmark(Post $post, User $user) 
{
    $userBookmarks = $user->myBookmarks->contains($post->id);
    
    return $userBookmarks ? 'bookmark-added' : '';
}
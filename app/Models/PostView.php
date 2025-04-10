<?php

namespace App\Models;

use App\Models\Post;
use Illuminate\Database\Eloquent\Model;

class PostView extends Model
{

    protected $fillable = [
        'post_id',
        'user_id',
        'ip_address'
    ];

    public function post()
    {
        // One PostView belongs to one Post
        return $this->belongsTo(Post::class);
    }
}

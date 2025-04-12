<?php

namespace App\Models;

use App\Models\User;
use App\Models\PostView;
use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use Searchable;
    
    public function toSearchableArray()
    {
        return [
            'title' => $this->title,
            'body' => $this->body
        ];
    }
    
    public function getUppercasedTitleAttribute()
    {
        return ucfirst($this->title);
    }

    protected $fillable = [
        'title',
        'body',
        'user_id',
        'slug',
        'viewCount'
    ];

    public function getUser()
    {
        // Post belongs to user with id of user_id
        return $this->belongsTo(User::class, 'user_id');
    }

    public function views()
    {
        // One post can have many views
        return $this->hasMany(PostView::class);
    }
}

<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

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

    protected $fillable = [
        'title',
        'body',
        'user_id'
    ];

    public function getUser()
    {
        // Post belongs to user with id of user_id
        return $this->belongsTo(User::class, 'user_id');
    }
}

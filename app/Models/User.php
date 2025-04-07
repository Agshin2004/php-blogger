<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Post;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
    ];

    protected function avatar(): Attribute
    {
        // Making use of Accessor (mutator is not used in our case)
        return Attribute::make(
            get: fn($value) => $value ? "/storage/avatars/{$value}" : '/fallback-avatar.jpg'
        );
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getPosts()
    {
        return $this->hasMany(Post::class, 'user_id');
    }

    public function followers()
    {
        return $this->hasMany(Follow::class, 'followeduser');
    }

    public function following()
    {
        return $this->hasMany(Follow::class, 'user_id');
    }

    public function feedPosts()
    {
        return $this->hasManyThrough(
            Post::class,
            Follow::class,
            'user_id',
            'user_id',
            'id',
            'followeduser'
        );
    }
}

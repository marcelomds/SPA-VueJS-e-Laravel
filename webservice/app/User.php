<?php

namespace App;

use App\Content;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;


    protected $fillable = [
        'name', 'email', 'password', 'image'
    ];


    protected $hidden = [
        'password', 'remember_token',
    ];


    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function contents()
    {
        return $this->hasMany(Content::class);
    }

    public function likes()
    {
        return $this->belongsToMany(Content::class, 'likes', 'user_id', 'content_id');
    }

    public function friends()
    {
        return $this->belongsToMany(User::class, 'friends', 'user_id', 'friend_id');
    }
}

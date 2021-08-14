<?php

namespace App;

use App\Content;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{

    protected $fillable = [
        'content_id',
        'description',
        'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function content()
    {
        return $this->belongsTo(Content::class);
    }

}

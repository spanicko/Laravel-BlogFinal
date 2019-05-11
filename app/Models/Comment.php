<?php

namespace App\Models;

use App\User;
use App\Models\Post;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'body',
        'user_id',
        'post_id'
    ];

    /**
     * Class constructor.
     *
     * @return App\Models\Comment
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($comment) {
            if(is_null($comment->user_id)) {
                $comment->user_id = auth()->user()->id;
            }
        });
    }

    /**
     * Returns post.
     *
     * @return App\Models\Post
     */
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Returns the user.
     *
     * @return App\User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

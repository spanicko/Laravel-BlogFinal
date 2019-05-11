<?php

namespace App\Models;

use App\User;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'title',
        'body',
        'user_id',
        'is_published'
    ];

    /**
     * Constructs the posts.
     *
     * @return App\Models\Post
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($post) {
            if(is_null($post->user_id)) {
                $post->user_id = auth()->user()->id;
            }
        });

        static::deleting(function ($post) {
            $post->comments()->delete();
        });
    }

    /**
     * Returns user model.
     *
     * @return App\User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Returns comment model.
     *
     * @return App\Models\Commment
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Returning all published posts.
     *
     * @return App\Models\Post
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Returns all the drafted posts.
     *
     * @return App\Models\Post
     */
    public function scopeDrafted($query)
    {
        return $query->where('is_published', false);
    }

    /**
     * Returns string.
     *
     * @return App\Models\Post
     */
    public function getPublishedAttribute()
    {
        return ($this->is_published) ? 'Yes' : 'No';
    }
}

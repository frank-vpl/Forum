<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'category',
        'content',
    ];

    protected static function booted(): void
    {
        static::deleting(function (Post $post) {
            $commentIds = Comment::where('post_id', $post->id)->pluck('id');
            Notification::where('post_id', $post->id)
                ->whereIn('type', ['post_like', 'post_comment'])
                ->delete();
            if ($commentIds->isNotEmpty()) {
                Notification::whereIn('comment_id', $commentIds)
                    ->where('type', 'comment_reply')
                    ->delete();
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function likes(): HasMany
    {
        return $this->hasMany(PostLike::class);
    }

    public function views(): HasMany
    {
        return $this->hasMany(PostView::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Post extends Model
{
    protected $primaryKey = 'post_id';

    protected $fillable = [
        'title',
        'type',
        'slug',
        'content',
        'thumbnail',
        'is_published',
        'order',
        'author_id',
        'meta_title',
        'meta_description',
        'event_start_at',
        'event_end_at',
        'event_location',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'event_start_at' => 'datetime',
        'event_end_at' => 'datetime',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id', 'id');
    }

    public function categories()
    {
        return $this->belongsToMany(
            Category::class,
            'post_categories',
            'post_id',
            'category_id',
            'post_id',
            'category_id'
        );
    }
}

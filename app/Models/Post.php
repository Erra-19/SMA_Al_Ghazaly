<?php

namespace App\Models;

use App\Models\Concerns\HasApiId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Post extends Model
{
    use HasApiId;

    protected $primaryKey = 'post_id';

    protected $appends = ['id', 'image'];

    protected $fillable = [
        'title',
        'type',
        'slug',
        'content',
        'summary',
        'thumbnail',
        'category',
        'post_status',
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

    public function getImageAttribute(): ?string
    {
        return $this->thumbnail;
    }

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

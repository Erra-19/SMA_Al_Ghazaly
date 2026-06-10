<?php

namespace App\Models;

use App\Models\Concerns\HasApiId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Album extends Model
{
    use HasApiId;

    protected $primaryKey = 'album_id';

    protected $appends = ['id', 'cover_image'];

    protected $fillable = [
        'album_id',
	'title',
        'slug',
        'cover',
        'description',
        'is_published',
        'order',
	'author_id',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    public function getCoverImageAttribute(): ?string
    {
        return $this->cover;
    }

    public function medias(): BelongsToMany
    {
        return $this->belongsToMany(Media::class, 'album_medias', 'album_id', 'media_id')
                    ->withPivot('order')
                    ->orderByPivot('order');
    }

    public function author(): BelongsTo
    {
    	return $this->belongsTo(User::class, 'author_id', 'id');
    }
}

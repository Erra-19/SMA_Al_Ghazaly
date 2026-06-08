<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Album extends Model
{
    protected $primaryKey = 'album_id';

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

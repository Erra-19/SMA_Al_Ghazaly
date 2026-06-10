<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    protected $table = 'medias';

    protected $primaryKey = 'media_id';

    protected $appends = [
        'id',
        'name',
        'url',
    ];

    protected $fillable = [
        'uploader_id',
        'filename',
        'path',
        'mime_type',
        'size',
    ];

    protected $casts = [
        'size'        => 'integer',
    ];

    public function getIdAttribute(): int|string|null
    {
        return $this->getAttribute('media_id');
    }

    public function getNameAttribute(): ?string
    {
        return $this->getAttribute('filename');
    }

    public function getUrlAttribute(): ?string
    {
        $path = $this->getAttribute('path');

        return $path ? Storage::disk('public')->url($path) : null;
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploader_id');
    }

    public function albums(): BelongsToMany
    {
        return $this->belongsToMany(Album::class, 'album_medias', 'media_id', 'album_id')
                    ->withPivot('order');
    }
}

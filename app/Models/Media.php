<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Media extends Model
{
    protected $table = 'medias';

    protected $primaryKey = 'media_id';

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

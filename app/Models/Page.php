<?php

namespace App\Models;

use App\Models\Concerns\HasApiId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Page extends Model
{
    use HasApiId;

    protected $primaryKey = 'page_id';

    protected $appends = ['id'];

    protected $fillable = [
        'title',
        'slug',
        'content',
        'thumbnail',
        'is_published',
		'author_id',
        'order',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    public function author(): BelongsTo
    {
    	return $this->belongsTo(User::class, 'author_id', 'id');
    }
}

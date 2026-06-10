<?php

namespace App\Models;

use App\Models\Concerns\HasApiId;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasApiId;

    protected $primaryKey = 'program_id';

    protected $appends = ['id'];

    protected $fillable = [
        'title',
        'slug',
        'type',
        'subtitle',
        'description',
        'image',
        'icon',
        'badge',
        'stats',
        'features',
        'order',
        'is_published',
    ];

    protected $casts = [
        'features' => 'array',
        'is_published' => 'boolean',
    ];
}

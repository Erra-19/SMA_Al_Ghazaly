<?php

namespace App\Models;

use App\Models\Concerns\HasApiId;
use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    use HasApiId;

    protected $primaryKey = 'facility_id';

    protected $appends = ['id'];

    protected $fillable = [
        'name',
        'slug',
        'category',
        'image',
        'icon_name',
        'short_desc',
        'long_desc',
        'capacity',
        'specs',
        'operational_hours',
        'location',
        'order',
        'is_featured',
        'is_published',
    ];

    protected $casts = [
        'specs' => 'array',
        'is_featured' => 'boolean',
        'is_published' => 'boolean',
    ];
}

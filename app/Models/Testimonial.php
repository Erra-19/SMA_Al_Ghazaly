<?php

namespace App\Models;

use App\Models\Concerns\HasApiId;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasApiId;

    protected $primaryKey = 'testimonial_id';

    protected $appends = ['id'];

    protected $fillable = [
        'name',
        'role',
        'photo',
        'rating',
        'content',
        'university',
        'major',
        'graduation_year',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alumnus extends Model
{
    protected $table = 'alumni';
    protected $primaryKey = 'alumnus_id';

    protected $fillable = [
        'name',
        'graduation_year',
        'photo',
        'current_institution',
        'major',
        'achievement',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];
}

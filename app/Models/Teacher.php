<?php

namespace App\Models;

use App\Models\Concerns\HasApiId;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasApiId;

    protected $primaryKey = 'teacher_id';

    protected $appends = ['id'];

    protected $fillable = [
        'nip', 'name', 'photo', 'position', 'subject', 'email', 'bio', 'phone',
        'is_active', 'order',
        'category', 'education', 'philosophy', 'experience', 'tags', 'is_leadership',
    ];

    protected $casts = [
        'is_active'     => 'boolean',
        'is_leadership' => 'boolean',
        'tags'          => 'array',
    ];
}

<?php

namespace App\Models;

use App\Models\Concerns\HasApiId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Testimonial extends Model
{
    use HasApiId;

    protected $primaryKey = 'testimonial_id';

    protected $appends = ['id'];

    protected $fillable = [
        'alumnus_id',
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

    public function alumnus(): BelongsTo
    {
        return $this->belongsTo(Alumnus::class, 'alumnus_id', 'alumnus_id');
    }
}

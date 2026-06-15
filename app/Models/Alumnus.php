<?php

namespace App\Models;

use App\Models\Concerns\HasApiId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Alumnus extends Model
{
    use HasApiId;

    protected $table = 'alumni';
    protected $primaryKey = 'alumnus_id';

    protected $appends = ['id', 'occupation', 'story'];

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

    public function getOccupationAttribute(): ?string
    {
        return $this->current_institution;
    }

    public function getStoryAttribute(): ?string
    {
        return $this->achievement;
    }

    public function testimonial(): HasOne
    {
        return $this->hasOne(Testimonial::class, 'alumnus_id', 'alumnus_id');
    }
}

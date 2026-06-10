<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicCalendar extends Model
{
    protected $primaryKey = 'calendar_id';

    protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date',
        'category',
        'color',
        'academic_year',
        'is_published',
        'order',
    ];

    protected $casts = [
        'start_date'   => 'date',
        'end_date'     => 'date',
        'is_published' => 'boolean',
    ];

    /** Scope: hanya yang dipublish, urut start_date */
    public function scopePublished($query)
    {
        return $query->where('is_published', 1)->orderBy('start_date');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Student extends Model
{
    protected $primaryKey = 'student_id';

    protected $fillable = [
        'user_id', 'registration_id',
        'nis', 'nisn', 'nik',
        'name', 'gender', 'birth_place', 'birth_date',
        'address', 'phone', 'email', 'photo',
        'parent_name', 'parent_phone',
        'previous_school', 'academic_year', 'grade_level', 'major',
        'status', 'notes', 'order', 'is_active',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function registration(): BelongsTo
    {
        return $this->belongsTo(Registration::class, 'registration_id', 'registration_id');
    }
}

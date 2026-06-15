<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Form extends Model
{
    protected $primaryKey = 'form_id';

    protected $fillable = [
        'name',
        'type',
        'slug',
        'fields',
        'steps',
        'description',
        'is_active',
    ];

    protected $casts = [
        'fields'    => 'array',
        'steps'     => 'array',
        'is_active' => 'boolean',
    ];

    public function submissions(): HasMany
    {
        return $this->hasMany(FormSubmission::class, 'form_id', 'form_id');
    }
}

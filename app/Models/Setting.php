<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $primaryKey = 'setting_id';
    public $timestamps = false;

    protected $fillable = [
        'key',
        'value',
        'group',
    ];
}

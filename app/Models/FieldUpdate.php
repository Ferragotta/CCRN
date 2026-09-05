<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FieldUpdate extends Model
{
    protected $fillable = [
        'state_id',
        'title',
        'content',
        'author',
        'severity',
    ];

    public function stateProfile()
    {
        return $this->belongsTo(StateProfile::class, 'state_id');
    }
}

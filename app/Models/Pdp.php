<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pdp extends Model
{
    protected $fillable = [
        'staff_id',
        'staff_name',
        'department',
        'state',
        'review_period',
        'objective_score',
        'behaviour_score',
        'innovation_score',
        'total_score',
        'status',
        'supervisor_feedback',
    ];

    protected $casts = [
        'objective_score' => 'integer',
        'behaviour_score' => 'integer',
        'innovation_score' => 'integer',
        'total_score' => 'integer',
    ];

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }
}

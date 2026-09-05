<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ControlDeviation extends Model
{
    protected $fillable = [
        'case_id',
        'control_standard',
        'observed_deviation',
        'severity',
    ];

    public function case()
    {
        return $this->belongsTo(InvestigationCase::class, 'case_id');
    }
}

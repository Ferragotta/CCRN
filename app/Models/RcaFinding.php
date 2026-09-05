<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RcaFinding extends Model
{
    protected $fillable = [
        'case_id',
        'cause_type',
        'description',
        'contributing_factors',
    ];

    public function case()
    {
        return $this->belongsTo(InvestigationCase::class, 'case_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvestigationCase extends Model
{
    protected $fillable = [
        'case_ref',
        'complaint_id',
        'title',
        'lead_investigator',
        'status',
        'severity',
        'findings_summary',
        'closure_date',
    ];

    protected $casts = [
        'closure_date' => 'date',
    ];

    public function complaint()
    {
        return $this->belongsTo(Complaint::class, 'complaint_id');
    }

    public function rcaFindings()
    {
        return $this->hasMany(RcaFinding::class, 'case_id');
    }

    public function controlDeviations()
    {
        return $this->hasMany(ControlDeviation::class, 'case_id');
    }

    public function evidenceCustody()
    {
        return $this->hasMany(EvidenceCustody::class, 'case_id');
    }
}

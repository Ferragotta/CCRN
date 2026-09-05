<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    protected $fillable = [
        'complaint_ref',
        'category',
        'severity',
        'source',
        'state',
        'status',
        'summary',
        'details',
        'alleged_party',
        'submitted_by',
        'assigned_to_id',
        'incident_date',
        'triage_notes',
    ];

    protected $casts = [
        'incident_date' => 'date',
    ];

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to_id');
    }

    public function cap()
    {
        return $this->hasOne(Cap::class, 'complaint_id');
    }

    public function investigation()
    {
        return $this->hasOne(InvestigationCase::class, 'complaint_id');
    }
}

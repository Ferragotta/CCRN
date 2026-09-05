<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cap extends Model
{
    protected $fillable = [
        'cap_ref',
        'finding',
        'action_plan',
        'state',
        'priority',
        'status',
        'progress_pct',
        'due_date',
        'lead_id',
        'complaint_id',
    ];

    protected $casts = [
        'due_date' => 'date',
        'progress_pct' => 'integer',
    ];

    public function lead()
    {
        return $this->belongsTo(User::class, 'lead_id');
    }

    public function complaint()
    {
        return $this->belongsTo(Complaint::class, 'complaint_id');
    }

    public function evidences()
    {
        return $this->hasMany(CapEvidence::class, 'cap_id');
    }
}

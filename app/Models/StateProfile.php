<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StateProfile extends Model
{
    protected $fillable = [
        'name',
        'code',
        'cluster',
        'lead_name',
        'staff_count',
        'compliance_score',
        'status',
    ];

    protected $casts = [
        'staff_count' => 'integer',
        'compliance_score' => 'integer',
    ];

    public function updates()
    {
        return $this->hasMany(FieldUpdate::class, 'state_id');
    }
}

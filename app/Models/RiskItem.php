<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiskItem extends Model
{
    protected $fillable = [
        'risk_ref',
        'category',
        'title',
        'description',
        'likelihood',
        'impact',
        'risk_score',
        'status',
        'mitigation_strategy',
        'owner',
    ];

    protected $casts = [
        'likelihood' => 'integer',
        'impact' => 'integer',
        'risk_score' => 'integer',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvidenceCustody extends Model
{
    protected $fillable = [
        'case_id',
        'item_description',
        'collected_by',
        'collected_at',
        'custody_location',
        'file_hash',
    ];

    protected $casts = [
        'collected_at' => 'datetime',
    ];

    public function case()
    {
        return $this->belongsTo(InvestigationCase::class, 'case_id');
    }
}

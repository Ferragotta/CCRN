<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CapEvidence extends Model
{
    protected $fillable = [
        'cap_id',
        'file_name',
        'file_url',
        'notes',
        'uploaded_by',
    ];

    public function cap()
    {
        return $this->belongsTo(Cap::class, 'cap_id');
    }
}

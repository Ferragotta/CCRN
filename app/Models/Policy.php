<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Policy extends Model
{
    protected $fillable = [
        'policy_code',
        'title',
        'category',
        'version',
        'effective_date',
        'status',
        'document_url',
        'summary',
    ];

    protected $casts = [
        'effective_date' => 'date',
    ];

    public function acknowledgements()
    {
        return $this->hasMany(PolicyAcknowledgement::class, 'policy_id');
    }
}

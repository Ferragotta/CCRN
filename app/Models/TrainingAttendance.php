<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingAttendance extends Model
{
    protected $fillable = [
        'module_id',
        'user_id',
        'completed_at',
        'score',
        'certificate_url',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
        'score' => 'integer',
    ];

    public function module()
    {
        return $this->belongsTo(TrainingModule::class, 'module_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

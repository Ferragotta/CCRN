<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingModule extends Model
{
    protected $fillable = [
        'module_code',
        'title',
        'category',
        'duration_hours',
        'target_audience',
        'mandatory',
        'status',
    ];

    protected $casts = [
        'duration_hours' => 'integer',
        'mandatory' => 'boolean',
    ];

    public function attendances()
    {
        return $this->hasMany(TrainingAttendance::class, 'module_id');
    }
}

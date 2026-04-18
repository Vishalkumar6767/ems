<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    protected $fillable = ['worker_id', 'floor_id', 'date', 'check_in', 'check_out', 'status'];

    protected $casts = [
        'date' => 'date',
    ];

    public function worker(): BelongsTo
    {
        return $this->belongsTo(Worker::class);
    }

    public function floor(): BelongsTo
    {
        return $this->belongsTo(Floor::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Floor extends Model
{
    protected $fillable = ['factory_id', 'name', 'floor_number'];

    public function factory(): BelongsTo
    {
        return $this->belongsTo(Factory::class);
    }

    public function workers(): HasMany
    {
        return $this->hasMany(Worker::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FocusSession extends Model
{
    protected $fillable = [
        'user_id',
        'task_name',
        'duration',
        'completed_at',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

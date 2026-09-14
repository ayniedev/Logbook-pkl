<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InternshipAssignment extends Model
{
    protected $fillable = [
        'internship_id',
        'division_id',
        'mentor_id',
        'start_date',
        'duration_days',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'start_date' => 'date',
    ];

    /**
     * Get the intern (peserta PKL) for this assignment.
     */
    public function internship(): BelongsTo
    {
        return $this->belongsTo(User::class, 'internship_id');
    }

    /**
     * Get the division for this assignment.
     */
    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }

    /**
     * Get the mentor (pembimbing) for this assignment.
     */
    public function mentor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mentor_id');
    }
}

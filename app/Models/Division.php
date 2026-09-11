<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Division extends Model
{
    protected $fillable = [
        'name',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the mentors (pembimbing) in this division.
     */
    public function mentors(): HasMany
    {
        return $this->hasMany(User::class, 'division_id');
    }

    /**
     * Get the internship assignments for this division.
     */
    public function internshipAssignments(): HasMany
    {
        return $this->hasMany(InternshipAssignment::class);
    }
}

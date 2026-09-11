<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'role_id',
        'pembimbing_id',
        'division_id',
        'username',
        'name',
        'email',
        'password',
        'schools',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function role(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Role::class);
    }

    /**
     * Get the pembimbing (supervisor) for this user.
     */
    public function pembimbing(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pembimbing_id');
    }

    /**
     * Get the peserta (interns) assigned to this pembimbing.
     */
    public function peserta(): HasMany
    {
        return $this->hasMany(User::class, 'pembimbing_id');
    }

    /**
     * Get the logbooks owned by this user.
     */
    public function logbooks(): HasMany
    {
        return $this->hasMany(\App\Models\Logbook::class);
    }

    /**
     * Get the division this user belongs to (for Pembimbing).
     */
    public function division(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Division::class);
    }

    /**
     * Get the internship assignments where this user is the mentor.
     */
    public function mentorAssignments(): HasMany
    {
        return $this->hasMany(\App\Models\InternshipAssignment::class, 'mentor_id');
    }

    /**
     * Get the internship assignment for this user (for Internship).
     */
    public function internshipAssignment(): HasOne
    {
        return $this->hasOne(\App\Models\InternshipAssignment::class, 'internship_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Defense extends Model
{
    use HasFactory;

    protected $fillable = [
        'EnrollmentID',
        'ProposalID',
        'DefenseType',
        'Schedule',
        'OverallVerdict',
    ];

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class, 'EnrollmentID');
    }

    public function proposal(): BelongsTo
    {
        return $this->belongsTo(Proposal::class, 'ProposalID');
    }

    public function panel(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'DefensePanel', 'DefenseID', 'PanelistUserID')->withPivot('Status');
    }

    public function evaluations(): HasMany
    {
        return $this->hasMany(DefenseEvaluation::class, 'DefenseID');
    }
}

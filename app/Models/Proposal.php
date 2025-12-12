<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Proposal extends Model
{
    use HasFactory;

    protected $fillable = [
        'EnrollmentID',
        'ResearchTitle',
        'SubmissionDate',
        'Deadline',
        'Status',
    ];

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class, 'EnrollmentID');
    }

    public function approvals(): HasMany
    {
        return $this->hasMany(ProposalApproval::class, 'ProposalID');
    }

    public function defenses(): HasMany
    {
        return $this->hasMany(Defense::class, 'ProposalID');
    }

    public function group()
    {
        return $this->hasOneThrough(Group::class, Enrollment::class, 'EnrollmentID', 'GroupID', 'EnrollmentID', 'GroupID');
    }
}

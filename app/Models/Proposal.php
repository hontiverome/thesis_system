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
        'enrollment_id',
        'title',
        'status',
        'submission_date',
        'deadline',
        'abstract',
        'manuscript_path',
        'published_date',
    ];

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function approvals(): HasMany
    {
        return $this->hasMany(ProposalApproval::class);
    }

    public function defenses(): HasMany
    {
        return $this->hasMany(Defense::class);
    }
}

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
        'enrollment_id',
        'proposal_id',
        'defense_type',
        'schedule',
        'overall_verdict',
    ];

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function proposal(): BelongsTo
    {
        return $this->belongsTo(Proposal::class);
    }

    public function panel(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'defense_panel')->withPivot('status', 'evaluation')->withTimestamps();
    }
}

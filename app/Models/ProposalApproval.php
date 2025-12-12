<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProposalApproval extends Model
{
    use HasFactory;

    protected $fillable = [
        'ProposalID',
        'ApprovedUserID',
        'ApprovalRole',
        'Status',
        'Remarks',
    ];

    public function proposal(): BelongsTo
    {
        return $this->belongsTo(Proposal::class);
    }

    public function approvedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ApprovedUserID');
    }
}

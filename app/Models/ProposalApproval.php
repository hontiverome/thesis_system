<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProposalApproval extends Model
{
    use HasFactory;
    
    protected $table = 'ProposalApprovals'; 

    protected $table = 'ProposalApprovals';

    protected $primaryKey = 'ApprovalID';
    
    public $incrementing = false;
    
    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'ApprovalID',
        'ProposalID',
        'ApprovedUserID',
        'ApprovalRole',
        'Status',
        'Remarks',
    ];

    public function proposal(): BelongsTo
    {
        return $this->belongsTo(Proposal::class, 'ProposalID', 'ProposalID');
    }

    public function approvedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ApprovedUserID', 'UserID');
    }
}

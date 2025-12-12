<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Submission extends Model
{
    use HasFactory;
    
    protected $table = 'Submissions';

    protected $primaryKey = 'FileID';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'FileID',
        'ProposalID',
        'DefenseID',
        'FileType',
        'FilePath',
        'UploadedByUserID',
    ];

    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'UploadedByUserID', 'UserID');
    }

    public function proposal(): BelongsTo
    {
        return $this->belongsTo(Proposal::class, 'ProposalID', 'ProposalID');
    }

    public function defense(): BelongsTo
    {
        return $this->belongsTo(Defense::class, 'DefenseID', 'DefenseID');
    }
}

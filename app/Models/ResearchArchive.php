<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResearchArchive extends Model
{
    use HasFactory;

    protected $fillable = [
        'ProposalID',
        'AbstractFilePath',
        'FullManuscriptPath',
        'PublishedDate',
    ];

    public function proposal(): BelongsTo
    {
        return $this->belongsTo(Proposal::class);
    }
}

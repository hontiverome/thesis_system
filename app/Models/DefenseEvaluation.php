<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DefenseEvaluation extends Model
{
    use HasFactory;

    protected $table = 'DefenseEvaluations';

    protected $primaryKey = 'EvaluationID';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'EvaluationID',
        'DefenseID',
        'PanelistUserID',
        'Verdict',
    ];

    public function defense(): BelongsTo
    {
        return $this->belongsTo(Defense::class, 'DefenseID');
    }

    public function panelistUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'PanelistUserID');
    }
}

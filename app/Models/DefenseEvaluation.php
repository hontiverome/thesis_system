<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DefenseEvaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'defense_id',
        'panelist_user_id',
        'verdict',
    ];

    public function defense(): BelongsTo
    {
        return $this->belongsTo(Defense::class);
    }

    public function panelist(): BelongsTo
    {
        return $this->belongsTo(User::class, 'panelist_user_id');
    }
}

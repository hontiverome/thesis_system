<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DefensePanel extends Model
{
    use HasFactory;

    protected $table = 'defense_panel';

    protected $fillable = [
        'defense_id',
        'user_id',
        'status',
        'evaluation',
    ];

    public function defense(): BelongsTo
    {
        return $this->belongsTo(Defense::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

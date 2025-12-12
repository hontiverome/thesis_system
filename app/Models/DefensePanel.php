<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DefensePanel extends Model
{
    use HasFactory;

    protected $table = 'DefensePanel';

    protected $primaryKey = ['DefenseID', 'PanelistUserID'];

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'DefenseID',
        'PanelistUserID',
        'Status',
    ];

    public function defense(): BelongsTo
    {
        return $this->belongsTo(Defense::class, 'DefenseID', 'DefenseID');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'PanelistUserID', 'UserID');
    }
    
}

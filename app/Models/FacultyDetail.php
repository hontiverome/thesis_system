<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FacultyDetail extends Model
{
    use HasFactory;

    protected $table = 'FacultyDetails';

    protected $primaryKey = 'FacultyDetailID';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'FacultyDetailID',
        'UserID',
        'FacultyType',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'UserID', 'UserID');
    }
}

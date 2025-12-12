<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Group extends Model
{
    use HasFactory;

    protected $table = 'Groups';

    protected $primaryKey = 'GroupID';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'GroupID',
        'GroupCode',
        'YearLevel',
        'AdviserUserID',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'GroupMembers', 'GroupID', 'StudentUserID')->withPivot('GroupRole');
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'GroupMembers', 'GroupID', 'StudentUserID')->withPivot('GroupRole');
    }

    public function advisers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'GroupAdvisers', 'GroupID', 'AdviserUserID');
    }

    public function proposal(): HasOne
    {
        return $this->hasOne(Proposal::class);
    }

    public function enrollment(): HasOne
    {
        return $this->hasOne(Enrollment::class, 'GroupID', 'GroupID');
    }
}

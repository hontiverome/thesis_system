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

    protected $fillable = [
        'name',
        'group_code',
        'year_level',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'group_members', 'group_id', 'student_user_id')->withPivot('role')->withTimestamps();
    }

    public function advisers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'group_advisers', 'group_id', 'adviser_user_id')->withTimestamps();
    }

    public function proposal(): HasOne
    {
        return $this->hasOne(Proposal::class);
    }
}

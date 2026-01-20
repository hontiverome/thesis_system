<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class GroupMember extends Pivot
{
    protected $table = 'GroupMembers';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'GroupID',
        'StudentUserID',
        'GroupRole',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'StudentUserID', 'UserID');
    }

    public function group()
    {
        return $this->belongsTo(Group::class, 'GroupID', 'GroupID');
    }
}

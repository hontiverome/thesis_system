<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupAdviser extends Model
{
    use HasFactory;

    protected $table = 'group_advisers';

    protected $fillable = [
        'group_id',
        'adviser_user_id',
    ];
}

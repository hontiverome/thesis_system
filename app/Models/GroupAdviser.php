<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupAdviser extends Model
{
    use HasFactory;

    protected $table = 'GroupAdvisers';

    public $incrementing = false;

    protected $primaryKey = null;

    public $timestamps = false;

    protected $fillable = [
        'GroupID',
        'AdviserUserID',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Enrollment extends Model
{
    use HasFactory;

    protected $table = 'Enrollments';

    protected $primaryKey = 'EnrollmentID';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'EnrollmentID',
        'GroupID',
        'CourseID',
        'SchoolYear',
        'Semester',
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class, 'GroupID', 'GroupID');
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'CourseID', 'CourseID');
    }

    public function proposals()
    {
        return $this->hasMany(Proposal::class, 'EnrollmentID', 'EnrollmentID');
    }
}

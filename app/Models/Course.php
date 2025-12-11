<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    use HasFactory;

    protected $table = 'Courses';

    protected $primaryKey = 'CourseID';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'CourseID',
        'CourseName',
        'PrerequisiteCourseID',
    ];

    public function prerequisite(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'PrerequisiteCourseID', 'CourseID');
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class, 'CourseID', 'CourseID');
    }
}

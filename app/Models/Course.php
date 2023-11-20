<?php

namespace App\Models;

use App\Enums\CourseState;
use App\Enums\DaySpan;
use App\Enums\GradeGroup;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $casts = [
      'state' => CourseState::class,
      'day_span' => DaySpan::class,
      'grade_group' => GradeGroup::class,
    ];
}

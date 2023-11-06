<?php

namespace App\Models;

use App\Enums\CourseState;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $casts = [
      'state' => CourseState::class,
    ];
}

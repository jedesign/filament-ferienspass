<?php

use App\Enums\CourseState;
use App\Enums\DaySpan;
use App\Enums\GradeGroup;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('courses', static function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->enum('state', CourseState::values())->default(CourseState::DRAFT->value);
            $table->string('state_message')->nullable();
            $table->dateTime('start');
            $table->dateTime('end');
            $table->enum('day_span', DaySpan::values());
            $table->unsignedInteger('min_participants')->default(5);
            $table->unsignedInteger('max_participants');
            $table->enum('grade_group', GradeGroup::values())
                ->default(GradeGroup::ALL->value)
                ->comment('lower => 1. – 3. grade, intermediate => 4. – 6. grade, all => all grades');
            $table->string('meeting_point');
            $table->string('clothes')->nullable();
            $table->string('bring_along')->nullable();
            $table->float('price');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};

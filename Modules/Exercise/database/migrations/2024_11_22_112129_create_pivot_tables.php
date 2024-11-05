<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('muscle_default_exercise', function (Blueprint $table) {
            $table->id();
            $table->foreignId('default_exercise_id')->constrained('default_exercises')->onDelete('cascade');
            $table->foreignId('muscle_id')->constrained('muscles')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();

           // $table->unique(['default_exercise_id', 'muscle_id']);
        });

        Schema::create('muscle_customized_exercise', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customized_exercise_id')->constrained('customized_exercises')->onDelete('cascade');
            $table->foreignId('muscle_id')->constrained('muscles')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();

           // $table->unique(['customized_exercise_id', 'muscle_id']);
        });

        Schema::create('exercise_system_exercise', function (Blueprint $table) {
            $table->id();
            // Polymorphic relation for exercise (either DefaultExercise or CustomizedExercise)
            $table->morphs('exerciseable'); // Will create exerciseable_id and exerciseable_type

            // Foreign key to ExerciseSystemDefault or ExerciseSystemCustomized
            $table->foreignId('exercise_system_default_id')->nullable()->constrained('exercise_system_defaults')->onDelete('cascade');
            $table->foreignId('exercise_system_customized_id')->nullable()->constrained('exercise_system_customized')->onDelete('cascade');

            $table->timestamps();
            $table->softDeletes();

            // You can add unique constraints if needed
            // $table->unique(['exercise_system_default_id', 'exerciseable_id', 'exerciseable_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('muscle_default_exercise');
        Schema::dropIfExists('muscle_customized_exercise');
        Schema::dropIfExists('exercise_system_exercise');



    }
};

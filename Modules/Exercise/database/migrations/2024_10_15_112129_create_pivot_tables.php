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
            $table->foreignId('default_exercise_id')->constrained()->onDelete('cascade');
            $table->foreignId('muscle_id')->constrained()->onDelete('cascade');
            $table->timestamps();

           // $table->unique(['default_exercise_id', 'muscle_id']);
        });

        Schema::create('muscle_customized_exercise', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customized_exercise_id')->constrained()->onDelete('cascade');
            $table->foreignId('muscle_id')->constrained()->onDelete('cascade');
            $table->timestamps();

           // $table->unique(['customized_exercise_id', 'muscle_id']);
        });

        Schema::create('exercise_system_defaults_default_exercise', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exercise_system_default_id')->constrained()->onDelete('cascade');
            $table->foreignId('default_exercise_id')->constrained()->onDelete('cascade');
            $table->timestamps();

          //  $table->unique(['exercise_system_default_id', 'default_exercise_id']);
        });

        Schema::create('exercise_system_defaults_customized_exercise', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exercise_system_default_id')->constrained()->onDelete('cascade');
            $table->foreignId('customized_exercise_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            //$table->unique(['exercise_system_default_id', 'customized_exercise_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('muscle_default_exercise');
        Schema::dropIfExists('muscle_customized_exercise');
        Schema::dropIfExists('exercise_system_defaults_default_exercise');
        Schema::dropIfExists('exercise_system_defaults_customized_exercise');


    }
};

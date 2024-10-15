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
        Schema::create('user_system_exercises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // Assuming system can be default or customized; using polymorphic relation or separate fields
            $table->foreignId('exercise_system_default_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('exercise_system_customized_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('exercise_id'); // Depending on the exercise type
            $table->timestamps();

            // Ensure either default or customized system is set
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_system_exercises');
    }
};

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
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Assuming systems are user-specific
            // Assuming system can be default or customized; using polymorphic relation or separate fields
            $table->foreignId('exercise_system_default_id')->nullable()->constrained('exercise_system_defaults')->onDelete('cascade');
            $table->foreignId('exercise_system_customized_id')->nullable()->constrained('exercise_system_customized')->onDelete('cascade');
            $table->softDeletes();
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

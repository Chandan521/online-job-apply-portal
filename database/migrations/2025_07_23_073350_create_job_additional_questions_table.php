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
        Schema::create('job_additional_questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('job_id');
            $table->string('question');
            $table->enum('type', ['text', 'textarea', 'radio', 'checkbox', 'select']);
            $table->boolean('is_required')->default(true);
            $table->json('options')->nullable(); // for select/radio/checkbox
            $table->timestamps();
            $table->foreign('job_id')->references('id')->on('jobs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_additional_questions');
    }
};

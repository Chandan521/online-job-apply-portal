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
        Schema::create('job_application_answers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('application_id');
            $table->unsignedBigInteger('question_id');
            $table->text('answer')->nullable(); // can store JSON for checkbox
            $table->timestamps();

            $table->foreign('application_id')->references('id')->on('job_applications')->onDelete('cascade');
            $table->foreign('question_id')->references('id')->on('job_additional_questions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_application_answers');
    }
};

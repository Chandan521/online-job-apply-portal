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
        Schema::create('scheduled_interviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_application_id')->constrained()->onDelete('cascade');
            $table->foreignId('job_id')->constrained()->onDelete('cascade');
            $table->foreignId('recruiter_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamp('interview_datetime');
            $table->string('mode');
            $table->string('location')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['scheduled', 'rescheduled', 'cancelled', 'completed'])->default('scheduled');
            $table->enum('action_by', ['job_seeker', 'recruiter', 'admin'])
            ->nullable(); // Who cancelled/rescheduled
            $table->enum('cancelled_by', ['recruiter', 'job_seeker','admin'])->nullable();
            $table->enum('rescheduled_by', ['recruiter', 'job_seeker', 'admin'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scheduled_interviews');
    }
};

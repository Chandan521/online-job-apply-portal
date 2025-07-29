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
        Schema::create('application_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('job_application_id');
            $table->unsignedBigInteger('sender_id'); // recruiter or candidate
            $table->text('message');
            $table->string('file_path')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('job_application_id')->references('id')->on('job_applications')->onDelete('cascade');
            $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_messages');
    }
};

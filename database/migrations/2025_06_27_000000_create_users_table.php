<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('role')->default('job_seeker');
            $table->string('phone')->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->string('address')->nullable();
            $table->string('education')->nullable();
            $table->string('experience')->nullable();
            $table->string('resume')->nullable();
            $table->string('profile_photo')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->text('skills')->nullable();
            $table->text('about_me')->nullable();
            $table->string('status')->default('active');
            $table->unsignedBigInteger('profile_visits')->default(0);
            $table->unsignedBigInteger('parent_id')->nullable(); // For sub-users
            $table->json('permissions')->nullable();
            $table->rememberToken();
            $table->timestamp('last_login_at')->nullable();
            $table->string('last_login_ip')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};

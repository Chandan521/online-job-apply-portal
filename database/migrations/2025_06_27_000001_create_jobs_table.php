<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('company');
            $table->string('location');
            $table->string('salary');
            $table->string('type');
            $table->string('shift');
            $table->text('skills');
            $table->string('company_logo')->nullable();
            $table->string('cover_image')->nullable();
            $table->unsignedBigInteger('recruiter_id')->nullable();
            $table->foreign('recruiter_id')->references('id')->on('users')->onDelete('set null');
            $table->unsignedBigInteger('views')->default(0);
            $table->text('description')->nullable();
            $table->text('requirements')->nullable();
            $table->text('responsibilities')->nullable();
            $table->text('benefits')->nullable();
            $table->string('application_url')->nullable();
            $table->date('deadline')->nullable();
            $table->string('status')->default('active');
            $table->boolean('is_approved')->default(false);
            $table->boolean('is_remote')->default(false);
            $table->string('employment_level')->nullable();
            $table->string('industry')->nullable();
            $table->string('experience')->nullable();
            $table->string('education')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('jobs');
    }
};

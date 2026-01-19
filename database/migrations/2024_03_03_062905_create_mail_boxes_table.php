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
        Schema::create('mail_boxes', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('subject');
            $table->string('domain');
            $table->integer('category_id');
            $table->string('attachment')->nullable();
            $table->string('per_hour_limit')->default(40);
            $table->string('date')->nullable();
            $table->string('time')->nullable();
            $table->text('body');
            $table->text('template_code')->nullable();
            $table->string('status')->default('draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mail_boxes');
    }
};

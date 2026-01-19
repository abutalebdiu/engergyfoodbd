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
        Schema::create('histories', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['sms', 'email'])->default('sms')->nullable();
            $table->integer('group_id')->nullable();
            $table->text('subject')->nullable();
            $table->text('message')->nullable();
            $table->text('email')->nullable();
            $table->text('phone')->nullable();
            $table->string('code')->nullable();
            $table->integer('domain')->nullable();
            $table->string('template_code')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('histories');
    }
};

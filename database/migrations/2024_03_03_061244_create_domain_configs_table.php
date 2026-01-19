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
        Schema::create('domain_configs', function (Blueprint $table) {
            $table->id();
            $table->string('title', 191);
            $table->string('logo')->nullable();
            $table->string('domain')->unique();
            $table->text('note')->nullable();
            $table->text('config');
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('domain_configs');
    }
};

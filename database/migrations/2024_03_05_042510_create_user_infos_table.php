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
        Schema::create('user_infos', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->string('passport_no')->nullable();
            $table->string('passport_file')->nullable();
            $table->string('passport_expired_date')->nullable();
            $table->string('own_whatsapp')->nullable();
            $table->string('bd_whatsapp')->nullable();
            $table->text('facebook')->nullable();
            $table->text('twitter')->nullable();
            $table->text('linkedin')->nullable();
            $table->string('manager_name')->nullable();
            $table->string('manager_mobile')->nullable();
            $table->string('manager_whatsapp')->nullable();
            $table->string('manager_email')->nullable();
            $table->string('manager_address')->nullable();
            $table->integer('entry_id')->nullable();
            $table->integer('edit_id')->nullable();
            $table->timestamp('edit_at')->nullable();
            $table->integer('deleted_id')->nullable();
            $table->enum('status',['Pending','Active','Inactive','Deleted'])->default('Active');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_infos');
    }
};

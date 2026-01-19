<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('buyer_hotels', function (Blueprint $table) {
            $table->id();
            $table->integer('buyer_id')->nullable();
            $table->integer('hotel_id')->nullable();
            $table->string('check_in')->nullable();
            $table->string('check_out')->nullable();
            $table->integer('total_day')->nullable();
            $table->double('per_night_rate')->nullable();
            $table->double('other_cost')->nullable();
            $table->double('total_cost')->nullable();
            $table->text('note')->nullable();
            $table->integer('entry_id')->nullable();
            $table->integer('edit_id')->nullable();
            $table->timestamp('edit_at')->nullable();
            $table->integer('deleted_id')->nullable();
            $table->enum('payment_status', ['Unsettled', 'Settled'])->default('Unsettled');
            $table->enum('status', ['Pending', 'Active', 'Inactive', 'Deleted'])->default('Active');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buyer_hotels');
    }
};

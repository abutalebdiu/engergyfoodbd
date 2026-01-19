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
        Schema::create('hotel_payment_histories', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no')->nullable();
            $table->string('reference_no')->nullable(); // Hotel Invoice no
            $table->integer('buyer_hotel_id')->nullable();
            $table->double('other')->nullable();
            $table->double('vat')->nullable();
            $table->double('amount')->nullable();
            $table->string('attachment')->nullable();
            $table->integer('payment_method_id')->nullable();
            $table->integer('amount_id')->nullable();
            $table->integer('entry_id')->nullable();
            $table->integer('edit_id')->nullable();
            $table->timestamp('edit_at')->nullable();
            $table->integer('deleted_id')->nullable();
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
        Schema::dropIfExists('hotel_payment_histories');
    }
};

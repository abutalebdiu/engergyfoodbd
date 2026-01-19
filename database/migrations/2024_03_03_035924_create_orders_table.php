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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('oid')->nullable();         
            $table->date('date')->nullable();
            $table->integer('customer_id')->nullable();
            $table->text('media')->nullable();
            $table->string('attachment')->nullable();
            $table->double('totalamount')->nullable();
            $table->enum('payment_status', ['Paid', 'Unpaid', 'Partial'])->default('Unpaid');
            $table->integer('entry_id')->nullable();
            $table->integer('edit_id')->nullable();
            $table->timestamp('edit_at')->nullable();
            $table->integer('deleted_id')->nullable();
            $table->integer('approved_id')->nullable();
            $table->timestamp('approved_at')->nullable();
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
        Schema::dropIfExists('orders');
    }
};

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
        Schema::create('transport_payment_histories', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no')->nullable();
            $table->string('reference_no')->nullable();
            $table->integer('warehouse_id')->unsigned()->nullable();
            $table->integer('transport_id')->unsigned()->nullable();
            $table->integer('buyer_id')->unsigned()->nullable();
            $table->integer('qty')->nullable();
            $table->integer('bag_qty')->nullable();
            $table->double('amount')->nullable();
            $table->integer('payment_method_id')->unsigned()->nullable();
            $table->integer('account_id')->unsigned()->nullable();
            $table->integer('buyer_account_id')->unsigned()->nullable();
            $table->integer('entry_id')->unsigned()->nullable();
            $table->integer('edit_id')->unsigned()->nullable();
            $table->timestamp('edit_at')->nullable();
            $table->integer('deleted_id')->unsigned()->nullable();
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
        Schema::dropIfExists('transport_payment_histories');
    }
};

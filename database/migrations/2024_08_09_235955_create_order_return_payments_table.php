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
        Schema::create('order_return_payments', function (Blueprint $table) {
            $table->id();
            $table->integer('order_return_id')->unsigned();
            $table->integer('customer_id')->unsigned();
            $table->date('date')->nullable();
            $table->double('amount')->nullable();
            $table->integer('payment_method_id')->unsigned();
            $table->integer('account_id')->unsigned();
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
        Schema::dropIfExists('order_return_payments');
    }
};

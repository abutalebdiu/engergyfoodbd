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
        Schema::create('order_payments', function (Blueprint $table) {
            $table->id();
            $table->string('tnx_no')->nullable();
            $table->integer('order_id')->unsigned();
            $table->integer('buyer_id')->unsigned();
            $table->double('amount')->nullable();
            $table->date('date')->nullable();
            $table->integer('payment_method_id')->unsigned();
            $table->integer('account_id')->unsigned();
            $table->integer('buyer_account_id')->unsigned();
            $table->string('attachment')->nullable();
            $table->text('note')->nullable();
            $table->integer('entry_id')->unsigned()->nullable();
            $table->integer('edit_id')->unsigned()->nullable();
            $table->timestamp('edit_at')->nullable();
            $table->integer('deleted_id')->nullable();
            $table->enum('status', ['Unpaid', 'Paid', 'Inactive', 'Deleted'])->default('Paid');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_payments');
    }
};

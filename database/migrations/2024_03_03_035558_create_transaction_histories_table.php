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
        Schema::create('transaction_histories', function (Blueprint $table) {
            $table->id();
            $table->string('txt_no')->nullable();
            $table->string('invoice_no')->nullable();
            $table->string('reference_no')->nullable();
            $table->integer('module_id')->nullable();
            $table->integer('module_invoice_id')->nullable();
            $table->double('amount')->nullable();
            $table->enum('cdf_type', ['credit', 'debit'])->default('credit');
            $table->integer('payment_method_id')->nullable();
            $table->integer('account_id')->nullable();
            $table->integer('buyer_account_id')->nullable();
            $table->integer('client_id')->nullable();
            $table->text('note')->nullable();
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
        Schema::dropIfExists('transaction_histories');
    }
};

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
        Schema::create('expense_payment_histories', function (Blueprint $table) {
            $table->id();
            $table->string('txt_no')->nullable();
            $table->string('ex_invoice_no')->nullable();
            $table->integer('expense_id')->nullable();
            $table->double('amount')->nullable();
            $table->integer('payment_method_id')->nullable();
            $table->integer('account_id')->nullable();
            $table->integer('entry_id')->nullable();
            $table->integer('edit_id')->nullable();
            $table->timestamp('edit_at')->nullable();
            $table->integer('deleted_id')->nullable();
            $table->enum('status', ['Pending', 'Approved', 'Inactive', 'Deleted'])->default('Approved');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expense_payment_histories');
    }
};

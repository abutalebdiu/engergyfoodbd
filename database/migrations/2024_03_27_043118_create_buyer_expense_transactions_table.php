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
        Schema::create('buyer_expense_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('tnx_no')->nullable();
            $table->string('invoice_no')->nullable();
            $table->integer('buyer_expense_category_id')->unsigned();
            $table->integer('buyer_id')->unsigned();
            $table->text('note')->nullable();
            $table->date('expense_date')->nullable();
            $table->double('amount', 30, 2)->nullable();
            $table->integer('payment_method_id')->unsigned();
            $table->integer('account_id')->unsigned();
            $table->integer('buyer_account_id')->unsigned();
            $table->integer('attachment')->nullable();
            $table->integer('entry_id')->nullable();
            $table->integer('edit_id')->nullable();
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
        Schema::dropIfExists('buyer_expense_transactions');
    }
};

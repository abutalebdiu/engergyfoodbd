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
        Schema::create('commission_invoice_payments', function (Blueprint $table) {
            $table->id();
            $table->string('tnx_no')->nullable();
            $table->integer('invoice_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->double('amount')->nullable();
            $table->double('less_amount')->nullable();
            $table->double('total_amount')->nullable();
            $table->date('date')->nullable();
            $table->integer('payment_method_id')->unsigned();
            $table->integer('account_id')->unsigned();
            $table->string('attachment')->nullable();
            $table->text('note')->nullable();
            $table->integer('entry_id')->nullable();
            $table->integer('edit_id')->nullable();
            $table->timestamp('edit_at')->nullable();
            $table->integer('deleted_id')->nullable();
            $table->enum('status', ['Pending', 'Active', 'Inactive', 'Deleted'])->default('Active');
            $table->enum('payment_status', ['Paid', 'Unpaid', 'Partial'])->default('Paid');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commission_invoice_payments');
    }
};
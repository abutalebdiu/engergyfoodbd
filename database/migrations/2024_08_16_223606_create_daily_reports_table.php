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
        Schema::create('daily_reports', function (Blueprint $table) {
            $table->id();
            $table->double('account_balance')->nullable();
            $table->double('order')->nullable();
            $table->double('order_payment')->nullable();
            $table->double('order_return')->nullable();
            $table->double('order_return_payment')->nullable();
            $table->double('purchase')->nullable();
            $table->double('purchase_payment')->nullable();
            $table->double('purchase_return')->nullable();
            $table->double('purchase_return_payment')->nullable();
            $table->double('salary_payment')->nullable();
            $table->double('loan')->nullable();
            $table->double('salary_advance')->nullable();
            $table->double('expense')->nullable();
            $table->double('expense_payment')->nullable();
            $table->double('service_amount')->nullable();           
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
        Schema::dropIfExists('daily_reports');
    }
};

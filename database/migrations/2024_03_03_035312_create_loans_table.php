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
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no')->nullable();
            $table->integer('employee_id')->nullable();
            $table->date('date')->nullable();
            $table->double('amount')->nullable();
            $table->double('interest')->nullable();
            $table->double('total_amount')->nullable();
            $table->double('monthly_settlement')->nullable();
            $table->integer('start_month_id')->nullable();
            $table->integer('payment_method_id')->nullable();
            $table->integer('amount_id')->nullable();
            $table->integer('entry_id')->nullable();
            $table->integer('edit_id')->nullable();
            $table->timestamp('edit_at')->nullable();
            $table->integer('approved_id')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->integer('deleted_id')->nullable();
            $table->enum('status', ['Pending', 'Approved', 'Rejected', 'Deleted'])->default('Approved');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};

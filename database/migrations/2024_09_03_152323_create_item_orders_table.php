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
        Schema::create('item_orders', function (Blueprint $table) {
            $table->id();
            $table->string('iid')->nullable();
            $table->date('date')->nullable();
            $table->double('subtotal', 10, 2)->nullable();
            $table->double('vat', 10, 2)->nullable();
            $table->double('tax', 10, 2)->nullable();
            $table->double('transport_cost', 10, 2)->nullable();
            $table->double('amount', 10, 2)->nullable();
            $table->enum('payment_status', ['Paid', 'Unpaid', 'Partial'])->default('Unpaid');
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
        Schema::dropIfExists('item_orders');
    }
};

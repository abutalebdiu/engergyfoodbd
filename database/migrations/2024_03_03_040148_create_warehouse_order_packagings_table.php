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
        Schema::create('warehouse_order_packagings', function (Blueprint $table) {
            $table->id();
            $table->integer('buyer_id')->nullable();
            $table->integer('order_id')->nullable();
            $table->date('date')->nullable();
            $table->integer('qty')->nullable();
            $table->integer('per_bag_qty')->nullable();
            $table->integer('total_bag_qty')->nullable();
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
        Schema::dropIfExists('warehouse_order_packagings');
    }
};

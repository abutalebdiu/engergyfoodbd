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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('code')->nullable();
            $table->string('image')->nullable();
            $table->double('purchase_price', 8, 2)->nullable();
            $table->double('sale_price', 8, 2)->nullable();
            $table->integer('qty');
            $table->integer('category_id')->nullable();
            $table->integer('brand_id')->nullable();
            $table->integer('unit_id')->nullable();
            $table->integer('supplier_id')->nullable();
            $table->string('description')->nullable();
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
        Schema::dropIfExists('items');
    }
};

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
        Schema::create('order_checking_reports', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id')->nullable();
            $table->integer('order_detail_id')->nullable();
            $table->integer('supplier_id')->nullable();
            $table->integer('order_checking_type_id')->nullable();
            $table->date('date')->nullable();
            $table->string('attachment')->nullable();
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
        Schema::dropIfExists('order_checking_reports');
    }
};

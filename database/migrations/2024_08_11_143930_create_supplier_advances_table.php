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
        Schema::create('supplier_advances', function (Blueprint $table) {
            $table->id();
            $table->string('tnx_no')->nullable();
            $table->integer('supplier_id')->unsigned();
            $table->integer('payment_method_id')->unsigned();
            $table->integer('account_id')->unsigned();
            $table->double('amount')->nullable();
            $table->text('note')->nullable();
            $table->integer('entry_id')->nullable();
            $table->integer('edit_id')->nullable();
            $table->timestamp('edit_at')->nullable();
            $table->integer('deleted_id')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_advances');
    }
};

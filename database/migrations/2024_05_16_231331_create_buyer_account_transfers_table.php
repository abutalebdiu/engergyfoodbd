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
        Schema::create('buyer_account_transfers', function (Blueprint $table) {
            $table->id();
            $table->integer('payment_method_id')->unsigned();
            $table->integer('account_id')->unsigned();
            $table->integer('buyer_account_id')->unsigned();
            $table->double('amount',30,2);
            $table->integer('from_account_id')->unsigned();
            $table->integer('from_buyer_account_id')->unsigned();
            $table->text('note')->nullable();
            $table->integer('entry_id')->nullable();
            $table->integer('edit_id')->nullable();
            $table->timestamp('edit_at')->nullable();
            $table->integer('deleted_id')->nullable();
            $table->enum('status',['Pending','Settled','Inactive','Deleted'])->default('Settled');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buyer_account_transfers');
    }
};

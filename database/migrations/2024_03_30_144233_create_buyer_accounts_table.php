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
        Schema::create('buyer_accounts', function (Blueprint $table) {
            $table->id();
            $table->integer('payment_method_id')->nullable()->unsigned();
            $table->integer('account_id')->nullable()->unsigned();
            $table->integer('buyer_id')->nullable()->unsigned();
            $table->string('title')->nullable();
            $table->double('opening_balance')->nullable();
            $table->double('balance')->nullable()->default(0);
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
        Schema::dropIfExists('buyer_accounts');
    }
};

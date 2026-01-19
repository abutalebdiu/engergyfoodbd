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
        Schema::create('dinos', function (Blueprint $table) {
            $table->id();
            $table->double('one_qty')->nullable();
            $table->double('one_amount')->nullable();
            $table->double('two_qty')->nullable();
            $table->double('two_amount')->nullable();
            $table->double('five_qty')->nullable();
            $table->double('five_amount')->nullable();
            $table->double('ten_qty')->nullable();
            $table->double('ten_amount')->nullable();
            $table->double('twenty_qty')->nullable();
            $table->double('twenty_amount')->nullable();
            $table->double('fifty_qty')->nullable();
            $table->double('fifty_amount')->nullable();
            $table->double('hundred_qty')->nullable();
            $table->double('hundred_amount')->nullable();
            $table->double('two_hundred_qty')->nullable();
            $table->double('two_hundred_amount')->nullable();
            $table->double('five_hundred_qty')->nullable();
            $table->double('five_hundred_amount')->nullable();
            $table->double('thousand_qty')->nullable();
            $table->double('thousand_amount')->nullable();
            $table->date('date')->nullable();
            $table->double('total_amount')->nullable();          
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
        Schema::dropIfExists('dinos');
    }
};

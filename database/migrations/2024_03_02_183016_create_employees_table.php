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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('emp_id')->nullable();
            $table->string('designation')->nullable();
            $table->string('mobile')->nullable();
            $table->string('email')->nullable();
            $table->string('father')->nullable();
            $table->string('mother')->nullable();
            $table->integer('nid')->nullable();
            $table->date('dob')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->string('education')->nullable();
            $table->date('joindate')->nullable();
            $table->string('emergency_contact')->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->text('attachment')->nullable();
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
        Schema::dropIfExists('employees');
    }
};

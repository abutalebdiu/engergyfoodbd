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
        Schema::create('attendance_applications', function (Blueprint $table) {
            $table->id();
            $table->integer('employee_id')->nullable();
            $table->integer('leave_type_id')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_time')->nullable();
            $table->string('day')->nullable();
            $table->date('approved_start_date')->nullable();
            $table->date('approved_end_time')->nullable();
            $table->string('attachment')->nullable();
            $table->integer('entry_id')->nullable();
            $table->integer('edit_id')->nullable();
            $table->timestamp('edit_at')->nullable();
            $table->integer('approved_id')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->integer('deleted_id')->nullable();
            $table->enum('status', ['Pending', 'Approved', 'Rejected', 'Deleted'])->default('Pending');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_applications');
    }
};

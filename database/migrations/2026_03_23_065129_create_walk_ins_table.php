<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('walk_ins', function (Blueprint $table) {
            $table->uuid('walkin_id')->primary();
            $table->uuid('employee_id');
            $table->foreign('employee_id')->references('employee_id')->on('employees')->onDelete('cascade');
            $table->string('guest_name');
            $table->string('phone')->nullable();
            $table->integer('num_guests');
            $table->date('check_in_date');
            $table->date('check_out_date');
            $table->string('status');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('walk_ins');
    }
};
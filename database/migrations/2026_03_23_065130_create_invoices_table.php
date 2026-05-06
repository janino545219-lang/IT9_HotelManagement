<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->uuid('invoice_id')->primary();
            $table->uuid('reservation_id');
            $table->foreign('reservation_id')->references('reservation_id')->on('reservations')->onDelete('cascade');
            $table->uuid('guest_id');
            $table->foreign('guest_id')->references('guest_id')->on('guests')->onDelete('cascade');
            $table->decimal('subtotal', 10, 2);
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2);
            $table->string('status');
            $table->timestamp('issued_at')->nullable();
            $table->date('due_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
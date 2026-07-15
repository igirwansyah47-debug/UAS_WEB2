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
        Schema::create('extra_bills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->string('title');
            $table->decimal('amount', 15, 2);
            $table->enum('status', ['unpaid', 'paid', 'failed'])->default('unpaid');
            $table->string('snap_token')->nullable();
            $table->string('transaction_id')->nullable();
            $table->timestamp('payment_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('extra_bills');
    }
};

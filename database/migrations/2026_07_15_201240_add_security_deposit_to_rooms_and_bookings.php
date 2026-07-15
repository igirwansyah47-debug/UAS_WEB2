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
        Schema::table('rooms', function (Blueprint $table) {
            $table->decimal('security_deposit', 15, 2)->default(0);
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->decimal('security_deposit', 15, 2)->default(0);
            $table->boolean('is_deposit_returned')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropColumn('security_deposit');
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['security_deposit', 'is_deposit_returned']);
        });
    }
};

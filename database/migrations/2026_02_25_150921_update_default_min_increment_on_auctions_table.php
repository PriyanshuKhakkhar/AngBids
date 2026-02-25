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
        Schema::table('auctions', function (Blueprint $table) {
            $table->decimal('min_increment', 10, 2)->default(100.00)->change();
        });

        // Update existing records that are still at the old default of 0.01
        \DB::table('auctions')->where('min_increment', 0.01)->update(['min_increment' => 100.00]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('auctions', function (Blueprint $table) {
            $table->decimal('min_increment', 10, 2)->default(0.01)->change();
        });
    }
};

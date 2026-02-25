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
            $table->decimal('starting_price', 18, 2)->change();
            $table->decimal('current_price', 18, 2)->change();
            $table->decimal('min_increment', 18, 2)->change();
        });

        Schema::table('bids', function (Blueprint $table) {
            $table->decimal('amount', 18, 2)->change();
        });

        Schema::table('auto_bids', function (Blueprint $table) {
            $table->decimal('max_bid_amount', 18, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('auctions', function (Blueprint $table) {
            $table->decimal('starting_price', 10, 2)->change();
            $table->decimal('current_price', 10, 2)->change();
            $table->decimal('min_increment', 10, 2)->change();
        });

        Schema::table('bids', function (Blueprint $table) {
            $table->decimal('amount', 10, 2)->change();
        });

        Schema::table('auto_bids', function (Blueprint $table) {
            $table->decimal('max_bid_amount', 10, 2)->change();
        });
    }
};

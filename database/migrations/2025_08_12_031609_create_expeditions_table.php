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
        Schema::create('expeditions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->string('order_number');
            $table->foreignId('customer_id')->constrained('customers');
            $table->string('input_date');
            $table->string('travel_date');
            $table->string('origin');
            $table->string('destination');
            $table->integer('distance');
            $table->foreignId('industry_sector_id')->constrained('industry_sectors');
            $table->string('description')->nullable();
            $table->foreignId('route_id')->constrained('routes');
            $table->string('detail_route')->nullable();
            $table->enum('expedition_type', ['vendor', 'fleet']);
            $table->foreignId('vendor_id')->nullable()->constrained('vendors');
            $table->foreignId('fleet_id')->nullable()->constrained('fleets');
            $table->foreignId('driver_id')->nullable()->constrained('drivers');
            $table->integer('eta')->comment('Estimated Time of Arrival in days');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expeditions');
    }
};

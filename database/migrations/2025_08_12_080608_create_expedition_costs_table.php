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
        Schema::create('expedition_cost_fleets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('expedition_id')->constrained('expeditions');
            $table->bigInteger('sales_amount')->nullable();
            $table->bigInteger('deposit_cost')->nullable();
            $table->date('deposit_date')->nullable();
            $table->bigInteger('transportation_cost')->nullable();
            $table->bigInteger('fuel_cost')->nullable();
            $table->bigInteger('tolling_cost')->nullable();
            $table->bigInteger('port_cost')->nullable();
            $table->bigInteger('insurance_cost')->nullable();
            $table->bigInteger('driver_cost')->nullable();
            $table->bigInteger('other_cost')->nullable();
            $table->string('description')->nullable();
            $table->bigInteger('total_cost')->nullable();
            $table->timestamps();
        });

        Schema::create('expedition_cost_vendors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('expedition_id')->constrained('expeditions');
            $table->bigInteger('sales_amount')->nullable();
            $table->bigInteger('vendor_cost')->nullable();
            $table->bigInteger('deposit_cost')->nullable();
            $table->date('deposit_date')->nullable();
            $table->bigInteger('other_cost')->nullable();
            $table->string('description')->nullable();
            $table->bigInteger('total_cost')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expedition_cost_fleets');
        Schema::dropIfExists('expedition_cost_vendors');
    }
};

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
            $table->integer('sales_amount')->nullable();
            $table->integer('deposit_cost')->nullable();
            $table->date('deposit_date')->nullable();
            $table->integer('transportation_cost')->nullable();
            $table->integer('fuel_cost')->nullable();
            $table->integer('tolling_cost')->nullable();
            $table->integer('port_cost')->nullable();
            $table->integer('insurance_cost')->nullable();
            $table->integer('driver_cost')->nullable();
            $table->integer('other_cost')->nullable();
            $table->string('description')->nullable();
            $table->integer('total_cost')->nullable();
            $table->timestamps();
        });

        Schema::create('expedition_cost_vendors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('expedition_id')->constrained('expeditions');
            $table->integer('sales_amount')->nullable();
            $table->integer('vendor_cost')->nullable();
            $table->integer('deposit_cost')->nullable();
            $table->date('deposit_date')->nullable();
            $table->integer('other_cost')->nullable();
            $table->string('description')->nullable();
            $table->integer('total_cost')->nullable();
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

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
        // Expeditions
        Schema::create('expeditions', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->string('input_date');
            $table->date('etd');
            $table->string('origin');
            $table->string('destination');
            $table->integer('distance');
            $table->text('description')->nullable();
            $table->foreignId('industry_sector_id')->constrained('industry_sectors');
            $table->foreignId('route_id')->constrained('routes');
            $table->text('detail_route')->nullable();
            $table->enum('expedition_type', ['vendor', 'fleet']);
            $table->foreignId('vendor_id')->nullable()->constrained('vendors');
            $table->foreignId('fleet_id')->nullable()->constrained('fleets');
            $table->foreignId('driver_id')->nullable()->constrained('drivers');
            $table->foreignId('customer_id')->constrained('customers');
            $table->foreignId('consignee_id')->constrained('consignees');
            $table->date('eta');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();
        });

        // Expedition Costs (Fleet)
        Schema::create('expedition_cost_fleets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('expedition_id')->constrained('expeditions')->onDelete('cascade');
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
            $table->text('description')->nullable();
            $table->integer('total_cost')->default(0);
            $table->timestamps();
        });

        // Expedition Costs (Vendor)
        Schema::create('expedition_cost_vendors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('expedition_id')->constrained('expeditions')->onDelete('cascade');
            $table->integer('sales_amount')->nullable();
            $table->integer('vendor_cost')->nullable();
            $table->integer('deposit_cost')->nullable();
            $table->date('deposit_date')->nullable();
            $table->integer('other_cost')->nullable();
            $table->text('description')->nullable();
            $table->integer('total_cost')->default(0);
            $table->timestamps();
        });

        // Expedition Goods
        Schema::create('expedition_goods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('expedition_id')->constrained('expeditions')->onDelete('cascade');
            $table->string('good_description')->nullable();
            $table->string('remark_dispatch')->nullable();
            $table->string('remark_receive')->nullable();
            $table->date('date_dispatch')->nullable();
            $table->date('date_receive')->nullable();
            $table->string('total_goods')->nullable();
            $table->integer('gross_weight')->nullable();
            $table->integer('dimension_total')->nullable();
            $table->string('packaging')->nullable();
            $table->string('special_instruction')->nullable();
            $table->timestamps();
        });

        // Expedition Goods Details
        Schema::create('expedition_good_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('expedition_good_id')->constrained('expedition_goods')->onDelete('cascade');
            $table->string('name');
            $table->string('quantity');
            $table->string('unit');
            $table->string('remark')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expedition_good_details');
        Schema::dropIfExists('expedition_goods');
        Schema::dropIfExists('expedition_cost_vendors');
        Schema::dropIfExists('expedition_cost_fleets');
        Schema::dropIfExists('expeditions');
    }
};

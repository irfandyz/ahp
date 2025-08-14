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
        // Industry Sectors
        Schema::create('industry_sectors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Routes
        Schema::create('routes', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Fleet Types
        Schema::create('fleet_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Vendors
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string('company');
            $table->text('address');
            $table->string('city');
            $table->string('pic');
            $table->string('title_pic');
            $table->string('phone');
            $table->string('moda');
            $table->string('fleet');
            $table->text('area_service_coverage');
            $table->timestamps();
        });

        // Customers
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('address');
            $table->string('phone');
            $table->string('email');
            $table->string('npwp');
            $table->string('pic_name');
            $table->string('pic_phone');
            $table->timestamps();
        });

        // Consignees
        Schema::create('consignees', function (Blueprint $table) {
            $table->id();
            $table->string('company');
            $table->text('address');
            $table->string('phone');
            $table->string('email');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consignees');
        Schema::dropIfExists('customers');
        Schema::dropIfExists('vendors');
        Schema::dropIfExists('fleet_types');
        Schema::dropIfExists('routes');
        Schema::dropIfExists('industry_sectors');
    }
};

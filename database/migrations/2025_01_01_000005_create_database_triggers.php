<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create trigger function for fleet costs (MySQL/MariaDB compatible)
        DB::unprepared('
            CREATE TRIGGER fleet_costs_total_cost_trigger
            BEFORE INSERT ON expedition_cost_fleets
            FOR EACH ROW
            SET NEW.total_cost = COALESCE(NEW.transportation_cost, 0) + 
                                COALESCE(NEW.fuel_cost, 0) + 
                                COALESCE(NEW.tolling_cost, 0) + 
                                COALESCE(NEW.port_cost, 0) + 
                                COALESCE(NEW.insurance_cost, 0) + 
                                COALESCE(NEW.driver_cost, 0) + 
                                COALESCE(NEW.other_cost, 0);
        ');

        DB::unprepared('
            CREATE TRIGGER fleet_costs_total_cost_update_trigger
            BEFORE UPDATE ON expedition_cost_fleets
            FOR EACH ROW
            SET NEW.total_cost = COALESCE(NEW.transportation_cost, 0) + 
                                COALESCE(NEW.fuel_cost, 0) + 
                                COALESCE(NEW.tolling_cost, 0) + 
                                COALESCE(NEW.port_cost, 0) + 
                                COALESCE(NEW.insurance_cost, 0) + 
                                COALESCE(NEW.driver_cost, 0) + 
                                COALESCE(NEW.other_cost, 0);
        ');

        // Create trigger for vendor costs (MySQL/MariaDB compatible)
        DB::unprepared('
            CREATE TRIGGER vendor_costs_total_cost_trigger
            BEFORE INSERT ON expedition_cost_vendors
            FOR EACH ROW
            SET NEW.total_cost = COALESCE(NEW.vendor_cost, 0) + 
                                COALESCE(NEW.deposit_cost, 0) + 
                                COALESCE(NEW.other_cost, 0);
        ');

        DB::unprepared('
            CREATE TRIGGER vendor_costs_total_cost_update_trigger
            BEFORE UPDATE ON expedition_cost_vendors
            FOR EACH ROW
            SET NEW.total_cost = COALESCE(NEW.vendor_cost, 0) + 
                                COALESCE(NEW.deposit_cost, 0) + 
                                COALESCE(NEW.other_cost, 0);
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop triggers
        DB::unprepared('DROP TRIGGER IF EXISTS vendor_costs_total_cost_update_trigger;');
        DB::unprepared('DROP TRIGGER IF EXISTS vendor_costs_total_cost_trigger;');
        DB::unprepared('DROP TRIGGER IF EXISTS fleet_costs_total_cost_update_trigger;');
        DB::unprepared('DROP TRIGGER IF EXISTS fleet_costs_total_cost_trigger;');
    }
};

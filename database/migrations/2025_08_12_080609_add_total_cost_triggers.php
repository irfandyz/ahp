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
        // Check if tables exist before adding columns
        if (Schema::hasTable('expedition_cost_fleets')) {
            if (!Schema::hasColumn('expedition_cost_fleets', 'total_cost')) {
                Schema::table('expedition_cost_fleets', function (Blueprint $table) {
                    $table->bigInteger('total_cost')->nullable()->after('other_cost');
                });
            }
        }

        if (Schema::hasTable('expedition_cost_vendors')) {
            if (!Schema::hasColumn('expedition_cost_vendors', 'total_cost')) {
                Schema::table('expedition_cost_vendors', function (Blueprint $table) {
                    $table->bigInteger('total_cost')->nullable()->after('other_cost');
                });
            }
        }

        // Create triggers only if tables exist
        if (Schema::hasTable('expedition_cost_fleets')) {
            $this->createFleetCostTrigger();
        }
        
        if (Schema::hasTable('expedition_cost_vendors')) {
            $this->createVendorCostTrigger();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop triggers if they exist
        try {
            DB::unprepared('DROP TRIGGER IF EXISTS update_fleet_total_cost');
            DB::unprepared('DROP TRIGGER IF EXISTS update_fleet_total_cost_update');
            DB::unprepared('DROP TRIGGER IF EXISTS update_vendor_total_cost');
            DB::unprepared('DROP TRIGGER IF EXISTS update_vendor_total_cost_update');
        } catch (\Exception $e) {
            // Ignore errors if triggers don't exist
        }
        
        // Remove total_cost columns if they exist
        if (Schema::hasTable('expedition_cost_fleets') && Schema::hasColumn('expedition_cost_fleets', 'total_cost')) {
            Schema::table('expedition_cost_fleets', function (Blueprint $table) {
                $table->dropColumn('total_cost');
            });
        }
        
        if (Schema::hasTable('expedition_cost_vendors') && Schema::hasColumn('expedition_cost_vendors', 'total_cost')) {
            Schema::table('expedition_cost_vendors', function (Blueprint $table) {
                $table->dropColumn('total_cost');
            });
        }
    }

    /**
     * Create trigger for fleet cost total calculation
     */
    private function createFleetCostTrigger(): void
    {
        try {
            // Drop existing triggers if they exist
            DB::unprepared('DROP TRIGGER IF EXISTS update_fleet_total_cost');
            DB::unprepared('DROP TRIGGER IF EXISTS update_fleet_total_cost_update');

            // Create INSERT trigger
            $sql = "
            CREATE TRIGGER update_fleet_total_cost
            BEFORE INSERT ON expedition_cost_fleets
            FOR EACH ROW
            SET NEW.total_cost = COALESCE(NEW.transportation_cost, 0) + 
                                COALESCE(NEW.fuel_cost, 0) + 
                                COALESCE(NEW.tolling_cost, 0) + 
                                COALESCE(NEW.port_cost, 0) + 
                                COALESCE(NEW.insurance_cost, 0) + 
                                COALESCE(NEW.driver_cost, 0) + 
                                COALESCE(NEW.deposit_cost, 0) + 
                                COALESCE(NEW.other_cost, 0);
            ";
            
            DB::unprepared($sql);

            // Create UPDATE trigger
            $sql = "
            CREATE TRIGGER update_fleet_total_cost_update
            BEFORE UPDATE ON expedition_cost_fleets
            FOR EACH ROW
            SET NEW.total_cost = COALESCE(NEW.transportation_cost, 0) + 
                                COALESCE(NEW.fuel_cost, 0) + 
                                COALESCE(NEW.tolling_cost, 0) + 
                                COALESCE(NEW.port_cost, 0) + 
                                COALESCE(NEW.insurance_cost, 0) + 
                                COALESCE(NEW.driver_cost, 0) + 
                                COALESCE(NEW.deposit_cost, 0) + 
                                COALESCE(NEW.other_cost, 0);
            ";
            
            DB::unprepared($sql);
        } catch (\Exception $e) {
            // Log error but don't fail migration
            \Log::warning('Failed to create fleet cost triggers: ' . $e->getMessage());
        }
    }

    /**
     * Create trigger for vendor cost total calculation
     */
    private function createVendorCostTrigger(): void
    {
        try {
            // Drop existing triggers if they exist
            DB::unprepared('DROP TRIGGER IF EXISTS update_vendor_total_cost');
            DB::unprepared('DROP TRIGGER IF EXISTS update_vendor_total_cost_update');

            // Create INSERT trigger
            $sql = "
            CREATE TRIGGER update_vendor_total_cost
            BEFORE INSERT ON expedition_cost_vendors
            FOR EACH ROW
            SET NEW.total_cost = COALESCE(NEW.vendor_cost, 0) + 
                                COALESCE(NEW.deposit_cost, 0) + 
                                COALESCE(NEW.other_cost, 0);
            ";
            
            DB::unprepared($sql);

            // Create UPDATE trigger
            $sql = "
            CREATE TRIGGER update_vendor_total_cost_update
            BEFORE UPDATE ON expedition_cost_vendors
            FOR EACH ROW
            SET NEW.total_cost = COALESCE(NEW.vendor_cost, 0) + 
                                COALESCE(NEW.deposit_cost, 0) + 
                                COALESCE(NEW.other_cost, 0);
            ";
            
            DB::unprepared($sql);
        } catch (\Exception $e) {
            // Log error but don't fail migration
            \Log::warning('Failed to create vendor cost triggers: ' . $e->getMessage());
        }
    }
};

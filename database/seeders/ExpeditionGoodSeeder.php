<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Expedition;
use App\Models\ExpeditionGood;
use App\Models\ExpeditionGoodDetail;

class ExpeditionGoodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if expeditions exist
        $expeditionCount = Expedition::count();
        
        if ($expeditionCount === 0) {
            $this->command->error('No expeditions found in the database.');
            $this->command->info('Please run the following seeders first:');
            $this->command->info('  php artisan db:seed --class=ExpeditionSeeder');
            return;
        }

        $this->command->info("Found {$expeditionCount} expeditions. Seeding goods data...");

        // Get expeditions that don't already have goods
        $expeditionsWithoutGoods = Expedition::whereDoesntHave('expeditionGoods')->take(10)->get();
        
        if ($expeditionsWithoutGoods->isEmpty()) {
            $this->command->info('All expeditions already have goods data.');
            return;
        }

        $this->command->info("Seeding goods for {$expeditionsWithoutGoods->count()} expeditions...");

        foreach ($expeditionsWithoutGoods as $expedition) {
            try {
                // Create expedition goods
                $expeditionGoods = ExpeditionGood::create([
                    'expedition_id' => $expedition->id,
                    'good_description' => $this->getRandomGoodDescription(),
                    'remark_dispatch' => $this->getRandomDispatchRemark(),
                    'remark_receive' => $this->getRandomReceiveRemark(),
                    'date_dispatch' => $this->getRandomDispatchDate($expedition->etd),
                    'date_receive' => $this->getRandomReceiveDate($expedition->etd),
                    'total_goods' => rand(5, 50).' Units',
                    'gross_weight' => rand(100, 5000), // kg
                    'dimension_total' => rand(10, 200), // m³
                    'packaging' => $this->getRandomPackaging(),
                    'special_instruction' => $this->getRandomSpecialInstruction(),
                ]);

                // Create goods details
                $this->createGoodDetails($expeditionGoods);
                
                $this->command->info("✓ Created goods for expedition #{$expedition->order_number}");
                
            } catch (\Exception $e) {
                $this->command->error("✗ Failed to create goods for expedition #{$expedition->order_number}: " . $e->getMessage());
            }
        }

        $this->command->info('Expedition goods seeding completed!');
    }

    /**
     * Create goods details for the expedition
     */
    private function createGoodDetails(ExpeditionGood $expeditionGoods): void
    {
        $goodsTypes = [
            ['name' => 'Steel Pipes', 'quantity' => rand(10, 100), 'unit' => 'pcs'],
            ['name' => 'Construction Materials', 'quantity' => rand(5, 20), 'unit' => 'tons'],
            ['name' => 'Machinery Parts', 'quantity' => rand(1, 10), 'unit' => 'sets'],
            ['name' => 'Electronics', 'quantity' => rand(50, 200), 'unit' => 'boxes'],
            ['name' => 'Textiles', 'quantity' => rand(100, 500), 'unit' => 'rolls'],
            ['name' => 'Agricultural Products', 'quantity' => rand(20, 100), 'unit' => 'bags'],
            ['name' => 'Automotive Parts', 'quantity' => rand(5, 50), 'unit' => 'pcs'],
            ['name' => 'Chemical Products', 'quantity' => rand(10, 50), 'unit' => 'drums'],
            ['name' => 'Food Items', 'quantity' => rand(100, 1000), 'unit' => 'kg'],
            ['name' => 'Medical Supplies', 'quantity' => rand(20, 100), 'unit' => 'boxes'],
            ['name' => 'Industrial Equipment', 'quantity' => rand(1, 5), 'unit' => 'units'],
            ['name' => 'Raw Materials', 'quantity' => rand(50, 200), 'unit' => 'kg'],
            ['name' => 'Finished Products', 'quantity' => rand(100, 500), 'unit' => 'pcs'],
            ['name' => 'Spare Parts', 'quantity' => rand(10, 100), 'unit' => 'sets'],
            ['name' => 'Tools and Equipment', 'quantity' => rand(5, 50), 'unit' => 'boxes'],
        ];

        // Randomly select 3-7 goods for each expedition
        $selectedGoods = collect($goodsTypes)->random(rand(3, 7));

        foreach ($selectedGoods as $good) {
            ExpeditionGoodDetail::create([
                'expedition_good_id' => $expeditionGoods->id,
                'name' => $good['name'],
                'quantity' => $good['quantity'],
                'unit' => $good['unit'],
                'remark' => $this->getRandomRemark(),
            ]);
        }
    }

    /**
     * Get random dispatch remark
     */
    private function getRandomDispatchRemark(): string
    {
        $remarks = [
            'Goods packed and ready for dispatch',
            'All items verified before shipping',
            'Proper packaging applied for safe transport',
            'Dispatch completed as scheduled',
            'Items inspected and loaded',
            'Ready for pickup by carrier',
            'Dispatch authorized by supervisor',
            'All documentation completed',
            'Quality check passed',
            'Dispatch on time',
            'Items securely packaged',
            'Dispatch checklist completed',
            'All safety measures applied',
            'Dispatch area cleared',
            'Loading completed successfully',
        ];

        return $remarks[array_rand($remarks)];
    }

    /**
     * Get random receive remark
     */
    private function getRandomReceiveRemark(): string
    {
        $remarks = [
            'All items received in good condition',
            'Package arrived slightly damaged',
            'Received as expected',
            'Items verified upon receipt',
            'Delivery completed successfully',
            'Some items need inspection',
            'Received with minor delays',
            'All quantities confirmed',
            'Goods in excellent condition',
            'Received and stored properly',
            'Items unpacked and checked',
            'Receipt documentation completed',
            'Storage location assigned',
            'Quality inspection scheduled',
            'All items accounted for',
        ];

        return $remarks[array_rand($remarks)];
    }

    /**
     * Get random packaging type
     */
    private function getRandomPackaging(): string
    {
        $packaging = [
            'Wooden Crates',
            'Cardboard Boxes',
            'Steel Containers',
            'Plastic Pallets',
            'Fiber Drums',
            'Metal Cans',
            'Fabric Bags',
            'Glass Bottles',
            'Aluminum Foil',
            'Composite Materials',
            'Vacuum Sealed Bags',
            'Insulated Containers',
            'Shock Absorbing Packaging',
            'Climate Controlled Boxes',
            'Heavy Duty Crates',
        ];

        return $packaging[array_rand($packaging)];
    }

    /**
     * Get random remark for goods
     */
    private function getRandomRemark(): string
    {
        $remarks = [
            'Handle with care',
            'Fragile items',
            'Keep dry',
            'Temperature sensitive',
            'Heavy items',
            'Stack carefully',
            'Do not crush',
            'Keep upright',
            'Ventilation required',
            'Special handling needed',
            'Store in cool place',
            'Avoid direct sunlight',
            'Stack maximum 3 high',
            'Use lifting equipment',
            'Check before use',
        ];

        return $remarks[array_rand($remarks)];
    }

    /**
     * Get random dispatch date (before travel date)
     */
    private function getRandomDispatchDate(string $travelDate): string
    {
        $travel = \Carbon\Carbon::parse($travelDate);
        $dispatch = $travel->copy()->subDays(rand(1, 7));
        return $dispatch->format('Y-m-d');
    }

    /**
     * Get random receive date (after travel date)
     */
    private function getRandomReceiveDate(string $travelDate): string
    {
        $travel = \Carbon\Carbon::parse($travelDate);
        $receive = $travel->copy()->addDays(rand(1, 14));
        return $receive->format('Y-m-d');
    }

    private function getRandomGoodDescription(): string
    {
        $descriptions = [
            'Steel Pipes',
            'Construction Materials',
            'Machinery Parts',
            'Electronics',
            'Textiles',
            'Agricultural Products',
            'Automotive Parts',
            'Chemical Products',
            'Food Items',
            'Medical Supplies',
            'Industrial Equipment',
            'Raw Materials',
            'Finished Products',
            'Spare Parts',
            'Tools and Equipment',
        ];

        return $descriptions[array_rand($descriptions)];
    }

    private function getRandomSpecialInstruction(): string
    {
        $instructions = [
            'Harap diperhatikan pengiriman barang dengan hati-hati dan sesuai dengan ketentuan yang berlaku.',
        ];

        return $instructions[array_rand($instructions)];
    }
}

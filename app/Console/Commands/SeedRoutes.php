<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Database\Seeders\RouteSeeder;

class SeedRoutes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seed:routes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed the database with predefined transportation routes';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Seeding routes...');
        
        try {
            $seeder = new RouteSeeder();
            $seeder->run();
            
            $this->info('Routes seeded successfully!');
            return 0;
        } catch (\Exception $e) {
            $this->error('Error seeding routes: ' . $e->getMessage());
            return 1;
        }
    }
}

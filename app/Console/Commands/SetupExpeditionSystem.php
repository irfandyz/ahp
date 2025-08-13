<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class SetupExpeditionSystem extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expedition:setup {--fresh : Fresh migration and seeding}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup expedition system with migrations and seeders';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Setting up Expedition System...');

        try {
            if ($this->option('fresh')) {
                $this->info('🔄 Running fresh migrations...');
                Artisan::call('migrate:fresh', [], $this->getOutput());
                $this->info('✅ Fresh migrations completed');
            } else {
                $this->info('🔄 Running migrations...');
                Artisan::call('migrate', [], $this->getOutput());
                $this->info('✅ Migrations completed');
            }

            $this->info('🌱 Running seeders...');
            Artisan::call('db:seed', [], $this->getOutput());
            $this->info('✅ Seeders completed');

            $this->info('🎉 Expedition System setup completed successfully!');
            $this->info('');
            $this->info('📋 What was created:');
            $this->info('   • Database tables with triggers for auto-calculation');
            $this->info('   • Sample data for all master data');
            $this->info('   • Sample expeditions with proper cost calculations');
            $this->info('');
            $this->info('🔧 Next steps:');
            $this->info('   • Visit /expeditions to see the system in action');
            $this->info('   • Use Cost Configuration button to set fuel and driver costs');
            $this->info('   • Create new expeditions with auto-calculated costs');

        } catch (\Exception $e) {
            $this->error('❌ Setup failed: ' . $e->getMessage());
            $this->error('Please check your database connection and try again.');
            return 1;
        }

        return 0;
    }
}

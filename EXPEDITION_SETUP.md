# Expedition System Setup Guide

## Overview
Sistem Expedition dengan fitur auto-calculation total cost menggunakan database triggers dan Laravel models.

## Features
- ✅ Auto-calculation total cost untuk fleet dan vendor expeditions
- ✅ Database triggers untuk real-time calculation
- ✅ Cost configuration untuk fuel dan driver costs
- ✅ Sample data yang lengkap
- ✅ Responsive UI dengan Vue.js

## Prerequisites
- PHP 8.1+
- Laravel 10+
- MySQL 8.0+ atau MariaDB 10.5+
- Node.js & NPM (untuk frontend assets)

## Quick Setup

### 1. Install Dependencies
```bash
composer install
npm install
```

### 2. Environment Setup
Copy `.env.example` ke `.env` dan konfigurasi database:
```bash
cp .env.example .env
php artisan key:generate
```

### 3. Database Setup
```bash
# Fresh setup (recommended untuk development)
php artisan expedition:setup --fresh

# Atau setup incremental
php artisan expedition:setup
```

### 4. Build Frontend Assets
```bash
npm run build
# atau untuk development
npm run dev
```

## Manual Setup (Alternative)

### 1. Run Migrations
```bash
php artisan migrate
```

### 2. Run Seeders
```bash
php artisan db:seed
```

### 3. Run Specific Seeder
```bash
php artisan db:seed --class=ExpeditionSeeder
```

## Database Structure

### Tables Created
- `expeditions` - Main expedition data
- `expedition_cost_fleets` - Fleet cost details
- `expedition_cost_vendors` - Vendor cost details
- `customers` - Customer information
- `vendors` - Vendor information
- `fleets` - Fleet vehicles
- `drivers` - Driver information
- `routes` - Transportation routes
- `industry_sectors` - Business sectors

### Triggers Created
- **Fleet Cost Triggers**: Auto-calculate total cost from individual cost components
- **Vendor Cost Triggers**: Auto-calculate total cost from vendor, deposit, and other costs

## Auto-Calculation Formula

### Fleet Cost
```
total_cost = transportation_cost + fuel_cost + tolling_cost + port_cost + insurance_cost + driver_cost + deposit_cost + other_cost
```

### Vendor Cost
```
total_cost = vendor_cost + deposit_cost + other_cost
```

## Usage

### 1. Access System
Visit `/expeditions` untuk melihat daftar expeditions

### 2. Cost Configuration
- Klik button "Cost Configuration" di halaman expeditions
- Set harga solar per liter
- Set driver cost (per day atau per km)
- Konfigurasi disimpan di localStorage

### 3. Create Expedition
- Pilih expedition type (vendor atau fleet)
- Isi data expedition
- Gunakan "Auto Calculate" untuk fuel cost dan driver cost
- Total cost akan otomatis terhitung

### 4. Edit Expedition
- Edit cost values
- Total cost akan otomatis ter-update melalui triggers

## Troubleshooting

### Common Issues

#### 1. Table Not Found Error
```bash
# Pastikan migration sudah dijalankan
php artisan migrate:status
php artisan migrate
```

#### 2. Trigger Creation Failed
```bash
# Check MySQL user privileges
# Pastikan user memiliki CREATE TRIGGER permission
GRANT CREATE, TRIGGER ON database_name.* TO 'username'@'localhost';
```

#### 3. Seeder Failed
```bash
# Pastikan semua master data sudah ada
php artisan db:seed --class=CustomerSeeder
php artisan db:seed --class=VendorSeeder
php artisan db:seed --class=FleetSeeder
```

### Debug Commands
```bash
# Check migration status
php artisan migrate:status

# Check database connection
php artisan tinker
DB::connection()->getPdo();

# View created triggers
php artisan tinker
DB::select("SHOW TRIGGERS");
```

## Development

### Adding New Cost Fields
1. Update migration untuk menambah kolom baru
2. Update model dengan fillable dan casts
3. Update trigger formula
4. Update seeder untuk data sample

### Customizing Triggers
Triggers dapat dimodifikasi di migration file:
```sql
CREATE TRIGGER update_fleet_total_cost
BEFORE INSERT ON expedition_cost_fleets
FOR EACH ROW
SET NEW.total_cost = COALESCE(NEW.transportation_cost, 0) + 
                    COALESCE(NEW.fuel_cost, 0) + 
                    -- Add more cost fields here
                    COALESCE(NEW.other_cost, 0);
```

## Support
Jika ada masalah atau pertanyaan, silakan buat issue atau hubungi development team.

## License
Internal use only - Company proprietary software.

<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\AssetModelSeeder;
use Database\Seeders\AssetMaterialSeeder;
use Database\Seeders\GedungSeeder;
use Database\Seeders\SatuanSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Run RoleAndUserSeeder first
        // $this->call(RoleAndUserSeeder::class);
        
        // Run RolePermissionSeeder
        // $this->call(RolePermissionSeeder::class);
        
        // Run SatuanSeeder first (satuans needed by materials)
        $this->call(SatuanSeeder::class);
        
        // Run GedungSeeder first (gedungs needed by materials)
        $this->call(GedungSeeder::class);
        
        // Run AssetMaterialSeeder first (materials needed by models)
        $this->call(AssetMaterialSeeder::class);
        
        // Run AssetModelSeeder
        // $this->call(AssetModelSeeder::class);
    }
}

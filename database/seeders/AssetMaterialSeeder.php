<?php

namespace Database\Seeders;

use App\Models\AssetMaterial;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AssetMaterialSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run database seeds.
     */
    public function run(): void
    {
        // Clear existing data
        AssetMaterial::query()->delete();

        // Define materials with realistic data
        $materials = [
            [
                'material_code' => 'MAT-001',
                'name' => 'Resin',
                'type' => 'Chemical',
                'unit' => 'Liter',
                'quantity' => 500,
                'min_threshold' => 50,
                'supplier' => 'PT. Chemical Indonesia',
                'entry_date' => now()->subDays(30),
                'location' => 'Gudang 1',
                'gedung_id' => 'GDG-004',
                'description' => 'Resin untuk proses molding dan composite'
            ],
            [
                'material_code' => 'MAT-002',
                'name' => 'Hardener',
                'type' => 'Chemical',
                'unit' => 'Liter',
                'quantity' => 300,
                'min_threshold' => 30,
                'supplier' => 'PT. Chemical Indonesia',
                'entry_date' => now()->subDays(25),
                'location' => 'Gudang 1',
                'gedung_id' => 'GDG-004',
                'description' => 'Hardener untuk campuran resin'
            ],
            [
                'material_code' => 'MAT-003',
                'name' => 'Adhesive (Lem)',
                'type' => 'Chemical',
                'unit' => 'Kilogram',
                'quantity' => 200,
                'min_threshold' => 20,
                'supplier' => 'PT. Adhesive Tech',
                'entry_date' => now()->subDays(20),
                'location' => 'Gudang 2',
                'gedung_id' => 'GDG-004',
                'description' => 'Lem industrial untuk berbagai aplikasi'
            ],
            [
                'material_code' => 'MAT-004',
                'name' => 'Dempul',
                'type' => 'Chemical',
                'unit' => 'Kilogram',
                'quantity' => 150,
                'min_threshold' => 15,
                'supplier' => 'PT. Chemical Indonesia',
                'entry_date' => now()->subDays(15),
                'location' => 'Gudang 2',
                'gedung_id' => 'GDG-004',
                'description' => 'Dempul untuk finishing permukaan'
            ],
            [
                'material_code' => 'MAT-005',
                'name' => 'Cat Pilox',
                'type' => 'Paint',
                'unit' => 'Kaleng',
                'quantity' => 100,
                'min_threshold' => 10,
                'supplier' => 'PT. Paint Indonesia',
                'entry_date' => now()->subDays(10),
                'location' => 'Gudang 3',
                'gedung_id' => 'GDG-004',
                'description' => 'Cat Pilox untuk proteksi metal'
            ],
            [
                'material_code' => 'MAT-006',
                'name' => 'Sekrup',
                'type' => 'Fastener',
                'unit' => 'Pcs',
                'quantity' => 2000,
                'min_threshold' => 200,
                'supplier' => 'PT. Fastener Indonesia',
                'entry_date' => now()->subDays(5),
                'location' => 'Gudang 3',
                'gedung_id' => 'GDG-004',
                'description' => 'Sekrup berbagai ukuran untuk assembly'
            ],
            [
                'material_code' => 'MAT-007',
                'name' => 'Release Agent',
                'type' => 'Chemical',
                'unit' => 'Liter',
                'quantity' => 250,
                'min_threshold' => 25,
                'supplier' => 'PT. Chemical Indonesia',
                'entry_date' => now()->subDays(7),
                'location' => 'Gudang 4',
                'gedung_id' => 'GDG-004',
                'description' => 'Release agent untuk mold release'
            ],
            [
                'material_code' => 'MAT-008',
                'name' => 'Sheet Wax',
                'type' => 'Chemical',
                'unit' => 'Kilogram',
                'quantity' => 180,
                'min_threshold' => 18,
                'supplier' => 'PT. Wax Indonesia',
                'entry_date' => now()->subDays(3),
                'location' => 'Gudang 4',
                'gedung_id' => 'GDG-004',
                'description' => 'Sheet wax untuk surface treatment'
            ],
        ];

        // Insert materials
        foreach ($materials as $material) {
            AssetMaterial::create($material);
        }

        $this->command->info('Asset materials seeded successfully!');
        $this->command->info('Total materials created: ' . count($materials));
    }
}
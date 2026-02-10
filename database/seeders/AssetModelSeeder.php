<?php

namespace Database\Seeders;

use App\Models\AssetModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AssetModelSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run database seeds.
     */
    public function run(): void
    {
        // Clear existing data
        AssetModel::query()->delete();

        // Define model names only (sesuai daftar yang diberikan)
        $modelNames = [
            'Base plate',
            'Bearing cover',
            'Bearing Housing',
            'Bearing support',
            'Body Qdc',
            'Bracket',
            'Bushing',
            'Casing C Type',
            'Casing',
            'Casing Cover',
            'Connector qd',
            'Deflector',
            'Distance piece',
            'Elbow',
            'Gland',
            'Impeller nut',
            'Independent base plate',
            'Latern Ring',
            'Linering',
            'Motor plate',
            'Pump foot',
            'Pump plate',
            'Shaft sleeve',
            'Side Cover',
            'Suction Cover'
        ];

        // Insert models with basic data only
        foreach ($modelNames as $index => $name) {
            AssetModel::create([
                'model_code' => $this->generateModelCode($index + 1),
                'name' => $name,
                'type' => 'Casting', // Default type
                'material_id' => null, // Kosongkan, nanti diisi manual
                'manufactured_date' => null, // Kosongkan, nanti diisi manual
                'condition' => 'Good', // Default condition
                'location' => null, // Kosongkan, nanti diisi manual
                'description' => null, // Kosongkan, nanti diisi manual
            ]);
        }

        $this->command->info('Asset models seeded successfully!');
        $this->command->info('Total models created: ' . count($modelNames));
    }

    /**
     * Generate unique model code
     */
    private function generateModelCode(int $index): string
    {
        return 'MOD-' . str_pad($index, 3, '0', STR_PAD_LEFT);
    }
}
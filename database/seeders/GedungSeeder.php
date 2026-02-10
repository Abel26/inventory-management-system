<?php

namespace Database\Seeders;

use App\Models\Gedung;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GedungSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data
        Gedung::query()->delete();

        // Define gedungs with realistic data
        $gedungs = [
            [
                'gedung_id' => 'GDG-001',
                'nama' => 'Gedung Produksi A'
            ],
            [
                'gedung_id' => 'GDG-002',
                'nama' => 'Gedung Produksi B'
            ],
            [
                'gedung_id' => 'GDG-003',
                'nama' => 'Gedung Produksi C'
            ],
            [
                'gedung_id' => 'GDG-004',
                'nama' => 'Gudang Utama'
            ],
        ];

        // Insert gedungs
        foreach ($gedungs as $gedung) {
            Gedung::create($gedung);
        }

        $this->command->info('Gedungs seeded successfully!');
        $this->command->info('Total gedungs created: ' . count($gedungs));
    }
}
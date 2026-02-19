<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Satuan;

class SatuanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $satuans = [
            ['nama' => 'Kilogram', 'kode' => 'kg'],
            ['nama' => 'Gram', 'kode' => 'g'],
            ['nama' => 'Liter', 'kode' => 'l'],
            ['nama' => 'Mililiter', 'kode' => 'ml'],
            ['nama' => 'Meter', 'kode' => 'm'],
            ['nama' => 'Centimeter', 'kode' => 'cm'],
            ['nama' => 'Pieces', 'kode' => 'pcs'],
            ['nama' => 'Boxes', 'kode' => 'box'],
            ['nama' => 'Bottles', 'kode' => 'btl'],
            ['nama' => 'Sacks', 'kode' => 'sak'],
            ['nama' => 'Roll', 'kode' => 'roll'],
            ['nama' => 'Pack', 'kode' => 'pack'],
            ['nama' => 'Set', 'kode' => 'set'],
            ['nama' => 'Pair', 'kode' => 'pair'],
            ['nama' => 'Dozen', 'kode' => 'dz'],
            ['nama' => 'Ton', 'kode' => 'ton'],
            ['nama' => 'Kaleng', 'kode' => 'kaleng'],
        ];

        foreach ($satuans as $satuan) {
            Satuan::create($satuan);
        }
    }
}

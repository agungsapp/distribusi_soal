<?php

namespace Database\Seeders;

use App\Models\Periode;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PeriodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            [
                'tahun_ajaran' => '2023/2024',
                'semester' => 'ganjil'
            ],
            [
                'tahun_ajaran' => '2023/2024',
                'semester' => 'genap'
            ],
            [
                'tahun_ajaran' => '2024/2025',
                'semester' => 'ganjil'
            ],
            [
                'tahun_ajaran' => '2024/2025',
                'semester' => 'genap'
            ],
        ];

        foreach ($items as $item) {
            Periode::create($item);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\MataKuliah;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MataKuliahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            [
                'peminatan_id' => 1,
                'nama' => 'Pemrograman Dasar',
                'kode' => 'TI-001'
            ],
            [
                'peminatan_id' => 1,
                'nama' => 'Pemrograman Lanjut',
                'kode' => 'TI-002'
            ],
            [
                'peminatan_id' => 2,
                'nama' => 'Fuzzy Logic',
                'kode' => 'TI-AI-003'
            ],
            [
                'peminatan_id' => 3,
                'nama' => 'Mobile Security',
                'kode' => 'TI-MB-004'
            ],
        ];

        foreach ($items as $item) {
            MataKuliah::create($item);
        }
    }
}

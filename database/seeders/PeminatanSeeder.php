<?php

namespace Database\Seeders;

use App\Models\Peminatan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PeminatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            [
                'prodi_id' => 1,
                'nama' => 'Wajib'
            ],
            [
                'prodi_id' => 1,
                'nama' => 'AI (Artificial Intelegence)'
            ],
            [
                'prodi_id' => 1,
                'nama' => 'Mobile Programming'
            ],
        ];

        foreach ($items as $item) {
            Peminatan::create($item);
        }
    }
}

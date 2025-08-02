<?php

namespace Database\Seeders;

use App\Models\Prodi;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'administrator',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ]);
        User::factory()->create([
            'name' => 'dosen pertama',
            'email' => 'dosen1@gmail.com',
            'password' => Hash::make('admin123'),
        ]);
        User::factory()->create([
            'name' => 'dosen kedua',
            'email' => 'dosen2@gmail.com',
            'password' => Hash::make('admin123'),
        ]);

        Prodi::create([
            'nama' => 'Teknik Informatika',
        ]);

        $this->call(PeminatanSeeder::class);
        $this->call(PeriodeSeeder::class);
        $this->call(MataKuliahSeeder::class);
    }
}

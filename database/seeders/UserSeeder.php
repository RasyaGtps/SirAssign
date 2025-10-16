<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample guru users
        $ahmad = User::create([
            'name' => 'Pak Ahmad',
            'email' => 'ahmad@sekolah.id',
            'password' => Hash::make('password123'),
            'role' => 'guru',
            'nip' => '196501011990031001',
            'bio' => 'Guru Matematika dengan pengalaman 20 tahun mengajar'
        ]);

        $sari = User::create([
            'name' => 'Bu Sari',
            'email' => 'sari@sekolah.id', 
            'password' => Hash::make('password123'),
            'role' => 'guru',
            'nip' => '197203151995122002',
            'bio' => 'Guru Bahasa Indonesia yang passionate dalam teknologi pendidikan'
        ]);

        $budi = User::create([
            'name' => 'Pak Budi',
            'email' => 'budi@sekolah.id',
            'password' => Hash::make('password123'),
            'role' => 'guru',
            'nip' => '198405102010011003',
            'bio' => 'Guru IPA yang aktif dalam inovasi pembelajaran digital'
        ]);

        $dewi = User::create([
            'name' => 'Bu Dewi',
            'email' => 'dewi@sekolah.id',
            'password' => Hash::make('password123'),
            'role' => 'guru',
            'nip' => '199012252015032001',
            'bio' => 'Guru Sejarah yang mengintegrasikan teknologi dalam pembelajaran'
        ]);

        // Assign mapels to users (guru bisa megang lebih dari 3 mapel)
        $ahmad->mapels()->attach([1, 3]); // Matematika, IPA
        $sari->mapels()->attach([2, 6]); // Bahasa Indonesia, PAI
        $budi->mapels()->attach([3, 8]); // IPA, Geografi
        $dewi->mapels()->attach([4, 7, 5]); // IPS, Sejarah, Bahasa Inggris
    }
}

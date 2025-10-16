<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Mapel;

class MapelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mapels = [
            [
                'nama_mapel' => 'Matematika',
                'deskripsi' => 'Mata pelajaran Matematika untuk mengembangkan kemampuan berpikir logis dan analitis'
            ],
            [
                'nama_mapel' => 'Bahasa Indonesia',
                'deskripsi' => 'Mata pelajaran Bahasa Indonesia untuk mengembangkan kemampuan berkomunikasi'
            ],
            [
                'nama_mapel' => 'Ilmu Pengetahuan Alam',
                'deskripsi' => 'Mata pelajaran IPA untuk memahami fenomena alam dan lingkungan'
            ],
            [
                'nama_mapel' => 'Ilmu Pengetahuan Sosial',
                'deskripsi' => 'Mata pelajaran IPS untuk memahami kehidupan sosial dan bermasyarakat'
            ],
            [
                'nama_mapel' => 'Bahasa Inggris',
                'deskripsi' => 'Mata pelajaran Bahasa Inggris untuk komunikasi internasional'
            ],
            [
                'nama_mapel' => 'Pendidikan Agama Islam',
                'deskripsi' => 'Mata pelajaran Pendidikan Agama Islam untuk pembentukan karakter'
            ],
            [
                'nama_mapel' => 'Sejarah',
                'deskripsi' => 'Mata pelajaran Sejarah untuk memahami peristiwa masa lalu'
            ],
            [
                'nama_mapel' => 'Geografi',
                'deskripsi' => 'Mata pelajaran Geografi untuk memahami bumi dan lingkungannya'
            ]
        ];

        foreach ($mapels as $mapel) {
            Mapel::create($mapel);
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;

class MahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID'); // Menggunakan lokal Indonesia

        // Menambahkan data diri saya
        DB::table('mahasiswa')->insert([
            'nim' => '23091397196',
            'nama' => 'Ahmad Diksa S',
            'jenis_kelamin' => 'L',
            'alamat' => 'Jombang, Jawa Timur',
            'tanggal_lahir' => '2005-06-20',
            'program_studi' => 'manajemen informatika',
            'angkatan' => 2023,
            'email' => 'ahmad.23183@mhs.unesa.ac.id',
            'status' => 'Aktif',
            'agama' => 'Islam',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Membuat 20 data dummy menggunakan Faker
        for ($i = 0; $i < 20; $i++) {
            DB::table('mahasiswa')->insert([
                'nim' => $faker->unique()->numerify('2309#######'),
                'nama' => $faker->name,
                'jenis_kelamin' => $faker->randomElement(['L', 'P']),
                'alamat' => $faker->address,
                'tanggal_lahir' => $faker->date('Y-m-d', '2005-12-31'), // Batasi tanggal lahir
                'program_studi' => $faker->randomElement(['Teknik Informatika', 'Sistem Informasi', 'Manajemen Informatika']),
                'angkatan' => $faker->numberBetween(2019, 2023), // Batasi tahun angkatan
                'email' => $faker->unique()->safeEmail,
                'status' => $faker->randomElement(['Aktif', 'Cuti', 'Lulus', 'Drop Out']),
                'agama' => $faker->randomElement(['Islam', 'Kristen Protestan', 'Katolik', 'Hindu', 'Buddha', 'Konghucu']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
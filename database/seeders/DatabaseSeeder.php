<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Facility;
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
        // Admin user
        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        // Standard user
        User::factory()->create([
            'name' => 'Regular User',
            'email' => 'user@user.com',
            'password' => Hash::make('user123'),
            'role' => 'user',
        ]);

        // Facilities FSM UNDIP
        Facility::create([
            'name' => 'Ruang Sidang Utama (Gedung Acintya Prasada)',
            'location' => 'Gedung Acintya Prasada',
            'description' => 'Biasa direservasi untuk agenda formal berskala fakultas, seperti seminar nasional, yudisium, workshop, atau pertemuan instansi.',
            'image_url' => 'https://fsm.undip.ac.id/wp-content/uploads/2025/09/1.png',
        ]);

        Facility::create([
            'name' => 'Ruang Pertemuan / Ruang Sidang Departemen',
            'location' => 'Departemen (Informatika, Matematika, Kimia, dll.)',
            'description' => 'Ruang sidang yang bisa direservasi mahasiswa untuk keperluan seminar proposal, sidang skripsi, atau rapat organisasi besar jika sedang tidak dipakai kuliah.',
            'image_url' => 'https://fisika.fsm.undip.ac.id/v2/wp-content/uploads/2025/10/ruangsidang-1024x819.jpg',
        ]);

        Facility::create([
            'name' => 'Laboratorium Komputer / Komputasi',
            'location' => 'Area Laboratorium',
            'description' => 'Digunakan secara bergantian berdasarkan reservasi jadwal praktikum. Jika organisasi mahasiswa ingin mengadakan pelatihan, ruangan ini harus direservasi jauh-jauh hari.',
            'image_url' => 'https://fsm.undip.ac.id/wp-content/uploads/2025/09/6.png',
        ]);

        Facility::create([
            'name' => 'Laboratorium Riset Terpadu',
            'location' => 'Area Riset',
            'description' => 'Untuk penggunaan alat-alat analisis sensitif atau pengujian sampel penelitian jangka panjang.',
            'image_url' => 'https://mkim.fsm.undip.ac.id/wp-content/uploads/2025/01/WhatsApp-Image-2025-01-23-at-8.52.35-PM-2-e1737644674182.png',
        ]);

        Facility::create([
            'name' => 'Lapangan Olahraga FSM',
            'location' => 'Area FSM',
            'description' => 'Lapangan futsal, basket, dan voli di area FSM bisa direservasi oleh mahasiswa untuk latihan rutin atau kompetisi olahraga antar-angkatan/jurusan.',
            'image_url' => 'https://fsm.undip.ac.id/wp-content/uploads/2025/09/3.png',
        ]);

        Facility::create([
            'name' => 'Gedung Pertemuan Serbaguna',
            'location' => 'Area FSM',
            'description' => 'Untuk acara-acara besar yang membutuhkan kapasitas ratusan orang.',
            'image_url' => 'https://fsm.undip.ac.id/wp-content/uploads/2025/09/7.png',
        ]);
    }
}

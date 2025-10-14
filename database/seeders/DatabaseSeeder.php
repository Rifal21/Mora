<?php

namespace Database\Seeders;

use App\Models\BlogCategory;
use App\Models\Role;
use App\Models\User;
use App\Models\UserProfile;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        Role::create(['name' => 'Super Admin']);
        Role::create(['name' => 'User']);
        User::factory()->create([
            'name' => 'Rifal Kurniawan',
            'username' => 'rifalkur_',
            'email' => 'rifal@gmail.com',
            'role_id' => Role::where('name', 'Super Admin')->first()->id,
            'password' => bcrypt('falkur21'),
            'status' => 'active'
        ]);
        UserProfile::create([
            'user_id' => User::where('email', 'rifal@gmail.com')->first()->id,
            'full_name' => 'Rifal Kurniawan',
            'user_type' => 'pro',
            'phone_number' => '08123456789',
            'address' => 'Jl. Raya No. 123',
        ]);
        $categories = [
            ['name' => 'Tips Keuangan Pribadi', 'slug' => 'tips-keuangan-pribadi', 'status' => 'active'],
            ['name' => 'Investasi untuk Pemula', 'slug' => 'investasi-untuk-pemula', 'status' => 'active'],
            ['name' => 'Manajemen UMKM', 'slug' => 'manajemen-umkm', 'status' => 'active'],
            ['name' => 'Bisnis & Side Hustle', 'slug' => 'bisnis-dan-side-hustle', 'status' => 'active'],
            ['name' => 'Literasi Finansial', 'slug' => 'literasi-finansial', 'status' => 'active'],
            ['name' => 'Perencanaan Keuangan Keluarga', 'slug' => 'perencanaan-keuangan-keluarga', 'status' => 'active'],
            ['name' => 'Gaya Hidup Hemat', 'slug' => 'gaya-hidup-hemat', 'status' => 'active'],
            ['name' => 'Uang & Teknologi', 'slug' => 'uang-dan-teknologi', 'status' => 'active'],
            ['name' => 'Fintech & Dompet Digital', 'slug' => 'fintech-dan-dompet-digital', 'status' => 'active'],
            ['name' => 'Inspirasi Sukses Finansial', 'slug' => 'inspirasi-sukses-finansial', 'status' => 'active'],
            ['name' => 'Mindset Uang', 'slug' => 'mindset-uang', 'status' => 'active'],
            ['name' => 'Pajak & Legalitas Bisnis', 'slug' => 'pajak-dan-legalitas-bisnis', 'status' => 'active'],
            ['name' => 'Tren Ekonomi Digital', 'slug' => 'tren-ekonomi-digital', 'status' => 'active'],
            ['name' => 'Crypto & Blockchain', 'slug' => 'crypto-dan-blockchain', 'status' => 'active'],
            ['name' => 'Saham & Pasar Modal', 'slug' => 'saham-dan-pasar-modal', 'status' => 'active'],
            ['name' => 'Properti & Investasi Real Estate', 'slug' => 'properti-dan-investasi-real-estate', 'status' => 'active'],
            ['name' => 'Tabungan & Dana Darurat', 'slug' => 'tabungan-dan-dana-darurat', 'status' => 'active'],
            ['name' => 'Asuransi & Proteksi Finansial', 'slug' => 'asuransi-dan-proteksi-finansial', 'status' => 'active'],
            ['name' => 'Karier & Penghasilan Tambahan', 'slug' => 'karier-dan-penghasilan-tambahan', 'status' => 'active'],
            ['name' => 'Bisnis Online & E-Commerce', 'slug' => 'bisnis-online-dan-e-commerce', 'status' => 'active'],
            ['name' => 'Ekonomi Kreatif', 'slug' => 'ekonomi-kreatif', 'status' => 'active'],
            ['name' => 'Mentalitas Kaya', 'slug' => 'mentalitas-kaya', 'status' => 'active'],
            ['name' => 'Keuangan Freelance', 'slug' => 'keuangan-freelance', 'status' => 'active'],
            ['name' => 'Tips Finansial Anak Muda', 'slug' => 'tips-finansial-anak-muda', 'status' => 'active'],
            ['name' => 'Strategi Menabung & Investasi', 'slug' => 'strategi-menabung-dan-investasi', 'status' => 'active'],
            ['name' => 'Uang & Produktivitas', 'slug' => 'uang-dan-produktivitas', 'status' => 'active'],
            ['name' => 'Bisnis Startup', 'slug' => 'bisnis-startup', 'status' => 'active'],
            ['name' => 'Gaya Hidup Minimalis', 'slug' => 'gaya-hidup-minimalis', 'status' => 'active'],
            ['name' => 'Finansial Syariah', 'slug' => 'finansial-syariah', 'status' => 'active'],
            ['name' => 'Tren Konsumsi Generasi Z', 'slug' => 'tren-konsumsi-generasi-z', 'status' => 'active'],
        ];

        foreach ($categories as $category) {
            BlogCategory::create([
                'id' => Str::uuid(),
                ...$category,
            ]);
        }
    }
}

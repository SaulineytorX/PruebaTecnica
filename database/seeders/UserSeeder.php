<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\Card;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Miguel Ruiz',
            'email' => 'admin@admin.com',
            'password' => bcrypt('admin'),
            'Consent_ID2' => Str::random(30),  // Agregado para Consent_ID2
            'Consent_ID3' => Str::random(30),  // Agregado para Consent_ID3
        ]);
    }
}

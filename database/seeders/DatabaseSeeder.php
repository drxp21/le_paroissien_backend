<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Jour;
use App\Models\PermanenceConfession;
use App\Models\PermanenceMesse;
use App\Models\PermanencePretres;
use App\Models\PermanenceSecretariat;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        \App\Models\User::create([
            'name' => 'Test User',
            'email' => 'admin@admin.com',
            'password'=>Hash::make('adminadmin'),
            'role'=>'superadmin',
            'profile_photo_path'=>'profile-photos/placeholder-church.jpg'

        ]);
        Jour::factory()->create([
            'nom' => 'Lundi'
        ]);
        Jour::factory()->create([
            'nom' => 'Mardi'
        ]);
        Jour::factory()->create([
            'nom' => 'Mercredi'
        ]);
        Jour::factory()->create([
            'nom' => 'Jeudi'
        ]);
        Jour::factory()->create([
            'nom' => 'Vendredi'
        ]);
        Jour::factory()->create([
            'nom' => 'Samedi'
        ]);
        Jour::factory()->create([
            'nom' => 'Dimanche'
        ]);


    }
}

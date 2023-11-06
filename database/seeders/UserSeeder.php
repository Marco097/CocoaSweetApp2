<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        //para agregar el usuario admin
        User::create([
            'name' => 'Michelle',
            'email' => 'michelle2@gmail.com',
            'password' => bcrypt('12345678')
        ])->assignRole('Admin');

        User::factory(20)->create();
    }
}

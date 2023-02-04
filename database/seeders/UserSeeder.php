<?php

namespace Database\Seeders;

use App\Enums\RolesEnum;
use App\Models\Nationality;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (!User::count()) {
            User::create([
                'name' => 'Admin',
                'username' => 'admin',
                'email' => 'admin@app.com ',
                'password' => Hash::make('password'),
                'nationality_id' => Nationality::inRandomOrder()->first()->id,
                'role' => RolesEnum::ADMIN->value
            ]);
        }
    }
}

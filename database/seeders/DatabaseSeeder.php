<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'first_name' => 'john',
            'last_name' => 'doe',
            'email' => 'admin@admin.com',
            'password'=>Hash::make('12345')
        ]);
    }
}

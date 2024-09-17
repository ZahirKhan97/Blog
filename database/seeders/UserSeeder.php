<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'ali',
            'email' => 'ali@example.com',
            'password' => Hash::make('123456')
        ]);
        User::create([
            'name' => 'john',
            'email' => 'john@example.com',
            'password' => Hash::make('123456')
        ]);
        User::create([
            'name' => 'mark',
            'email' => 'mark@example.com',
            'password' => Hash::make('123456')
        ]);
    }
}

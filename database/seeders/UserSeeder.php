<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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
        DB::table('users')->insert([
            'name' => 'Kelly Tester',
            'email' => 'kelly@psu.edu',
            'password' => Hash::make('scheduleMe9'),
            'created_at' => now(),
            'email_verified_at' => now(),
        ]);

        DB::table('users')->insert([
            'name' => 'Ava Tester',
            'email' => 'ava@psu.edu',
            'password' => Hash::make('scheduleMe9'),
            'created_at' => now(),
            'email_verified_at' => now(),
        ]);

        DB::table('users')->insert([
            'name' => 'Jane NoDev',
            'email' => 'jane@psu.edu',
            'password' => Hash::make('scheduleMe9'),
            'created_at' => now(),
            'email_verified_at' => now(),
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use DB;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            //Admin
            [
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@email.com',
            'password' => Hash::make('111'),
            'role' => 'admin',
            'status' => 'active'
            ],

            //Vendor
            [
                'name' => 'Vendor',
                'username' => 'vendor',
                'email' => 'vendor@email.com',
                'password' => Hash::make('111'),
                'role' => 'vendor',
                'status' => 'active'
            ],

            //User
            [
                'name' => 'User',
                'username' => 'user',
                'email' => 'user@email.com',
                'password' => Hash::make('111'),
                'role' => 'user',
                'status' => 'active'
            ]
            
        ]);
    
    }
}

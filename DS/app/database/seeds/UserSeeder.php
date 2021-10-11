<?php

use App\Enums\UserRole;
use App\User;
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
        User::truncate();
        \App\GoogleAccount::truncate();
        User::create(
            [
                'username' => 'root',
                'password' => Hash::make('senha'),
                'user_role' => UserRole::Root()
            ]

        );
        User::create(
            [
                'username' => 'admin',
                'password' => Hash::make('123senha'),
                'user_role' => UserRole::Admin()
            ]
        );
        User::create(
            [
                'username' => 'estatistica',
                'password' => Hash::make('senha123'),
                'user_role' => UserRole::Statistics()
            ]
        );
        User::create(
            [
                'username' => 'tv',
                'password' => Hash::make('123'),
                'user_role' => UserRole::Screen()
            ]
        );


        //
    }
}

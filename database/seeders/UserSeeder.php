<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Database\Seeder;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name'     => 'Yuumi',
            'email'    => 'yuumi@gmail.com',
            'password' => Hash::make('yuumi12345'),
            'role_id'  => 2
        ]);
         User::create([
            'name'     => 'Hiro',
            'email'    => 'hiro@gmail.com',
            'password' => Hash::make('hiro12345'),
            'role_id'  => 2
        ]);
         User::create([
            'name'     => 'Haruka',
            'email'    => 'haruka@gmail.com',
            'password' => Hash::make('haru12345'),
            'role_id'  => 2
        ]);
         User::create([
            'name'     => 'Kamo',
            'email'    => 'kamo@gmail.com',
            'password' => Hash::make('kamo12345'),
            'role_id'  => 2
        ]);
         User::create([
            'name'     => 'Shem',
            'email'    => 'shem@gmail.com',
            'password' => Hash::make('shem12345'),
            'role_id'  => 2
        ]);
    }
}

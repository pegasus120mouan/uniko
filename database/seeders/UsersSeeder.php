<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Administrateur',
                'login' => 'admin',
                'email' => 'admin@uniko.test',
                'password' => Hash::make('admin12345'),
                'role' => 'admin',
            ],
            [
                'name' => 'Utilisateur Test',
                'login' => 'test',
                'email' => 'test@uniko.test',
                'password' => Hash::make('test12345'),
                'role' => 'staff',
            ],
        ];

        foreach ($users as $data) {
            User::updateOrCreate(
                ['login' => $data['login']],
                $data
            );
        }
    }
}

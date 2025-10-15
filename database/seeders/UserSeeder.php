<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = ['admin', 'driver', 'gudang', 'customer'];

        foreach ($roles as $roleName) {
            $user = User::firstOrCreate(
                ['email' => $roleName . '@gmail.com'],
                [
                    'id' => Str::uuid(),
                    'name' => ucfirst($roleName),
                    'password' => Hash::make('12345678'),
                ]
            );
            $user->assignRole($roleName);
        }
    }
}

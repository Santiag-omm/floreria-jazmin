<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->first();
        $employeeRole = Role::where('name', 'empleado')->first();
        $clientRole = Role::where('name', 'cliente')->first();

        $admin = User::firstOrCreate(
            ['email' => 'admin@floreria.test'],
            [
                'name' => 'Admin Florería',
                'password' => Hash::make('password'),
                'phone' => '555-0101',
                'address' => 'Calle Central 123',
            ]
        );
        $adminRole?->users()->syncWithoutDetaching([$admin->id]);

        $employee = User::firstOrCreate(
            ['email' => 'empleado@floreria.test'],
            [
                'name' => 'Empleado Florería',
                'password' => Hash::make('password'),
                'phone' => '555-0102',
                'address' => 'Av. Jardín 456',
            ]
        );
        $employeeRole?->users()->syncWithoutDetaching([$employee->id]);

        $client = User::firstOrCreate(
            ['email' => 'cliente@floreria.test'],
            [
                'name' => 'Cliente Demo',
                'password' => Hash::make('password'),
                'phone' => '555-0103',
                'address' => 'Col. Flores 789',
            ]
        );
        $clientRole?->users()->syncWithoutDetaching([$client->id]);
    }
}

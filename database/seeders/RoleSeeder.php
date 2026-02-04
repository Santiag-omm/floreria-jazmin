<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::firstOrCreate(['name' => 'admin'], ['label' => 'Administrador']);
        Role::firstOrCreate(['name' => 'empleado'], ['label' => 'Empleado']);
        Role::firstOrCreate(['name' => 'cliente'], ['label' => 'Cliente']);
    }
}

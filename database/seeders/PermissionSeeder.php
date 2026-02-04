<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'manage_categories' => 'Gestionar categorías',
            'manage_products' => 'Gestionar productos',
            'manage_promotions' => 'Gestionar promociones',
            'manage_orders' => 'Gestionar pedidos',
        ];

        foreach ($permissions as $name => $label) {
            Permission::firstOrCreate(['name' => $name], ['label' => $label]);
        }

        $admin = Role::where('name', 'admin')->first();
        $employee = Role::where('name', 'empleado')->first();

        if ($admin) {
            $admin->permissions()->sync(Permission::pluck('id'));
        }

        if ($employee) {
            $employee->permissions()->sync(Permission::pluck('id'));
        }
    }
}

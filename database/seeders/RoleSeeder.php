<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
  // Para ver en que vistas podra tener acceso segun el rol indicado
    public function run(): void
    {
        $role1 = Role::create((['name' => 'Admin']));
        $role2 = Role::create((['name' => 'User']));

                //Permisos para los roles del admin
                Permission::create(['name' => 'admin.dash'])->syncRoles([$role1]);

                Permission::create(['name' => 'admin.users.index'])->syncRoles([$role1]);
                Permission::create(['name' => 'admin.users.edit'])->syncRoles([$role1]);
                Permission::create(['name' => 'admin.users.update'])->syncRoles([$role1]);

                //Sabores
        Permission::create(['name' => 'admin.sabores.index'])->syncRoles([$role1]);
        Permission::create(['name' => 'admin.sabores.create'])->syncRoles([$role1]);
        Permission::create(['name' => 'admin.sabores.edit'])->syncRoles([$role1]);
        Permission::create(['name' => 'admin.sabores.destroy'])->syncRoles([$role1]);

        //Productos
        Permission::create(['name' => 'admin.productos.index'])->syncRoles([$role1]);
        Permission::create(['name' => 'admin.productos.create'])->syncRoles([$role1]);
        Permission::create(['name' => 'admin.productos.edit'])->syncRoles([$role1]);
        Permission::create(['name' => 'admin.productos.destroy'])->syncRoles([$role1]);

        //Rellenos
        Permission::create(['name' => 'admin.rellenos.index'])->syncRoles([$role1]);
        Permission::create(['name' => 'admin.rellenos.create'])->syncRoles([$role1]);
        Permission::create(['name' => 'admin.rellenos.edit'])->syncRoles([$role1]);
        Permission::create(['name' => 'admin.rellenos.destroy'])->syncRoles([$role1]);

        //Pedidos
        Permission::create(['name' => 'admin.pedidos.index'])->syncRoles([$role1]);
        Permission::create(['name' => 'admin.pedidos.create'])->syncRoles([$role1]);
        Permission::create(['name' => 'admin.pedidos.edit'])->syncRoles([$role1]);
        Permission::create(['name' => 'admin.pedidos.destroy'])->syncRoles([$role1]);

        //Categorias
        Permission::create(['name' => 'admin.categorias.index'])->syncRoles([$role1]);
        Permission::create(['name' => 'admin.categorias.create'])->syncRoles([$role1]);
        Permission::create(['name' => 'admin.categorias.edit'])->syncRoles([$role1]);
        Permission::create(['name' => 'admin.categorias.destroy'])->syncRoles([$role1]);

    }
}


<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Role::create(['name' => 'admin']);
        Role::create(['name' => 'user']);
        $permissions = [
            'business',
            'customer',
            'product',
            'terms',
            'quotation',
            'invoice',
            'purchase order',
            'proforma invoice',
            'quotation list',
            'invoice list',
            'purchase order list',
            'proforma invoice list'
        ];

        foreach($permissions as $permission){
            $per = Permission::create(['name' => $permission]);
            $role->givePermissionTo($per);
        }
        
    }
}

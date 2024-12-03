<?php

namespace Database\Seeders\Permission;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    
    public function run(): void
    {

        $superAdmin = User::find(1);

        $superAdminRole = Role::firstOrCreate([
            'name' => 'sysAdmin',
            'guard_name' => 'api'
        ]);

        $adminRole = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'api'
        ]);

        $memberRole = Role::firstOrCreate([
            'name' => 'member',
            'guard_name' => 'api'
        ]);


        Permission::firstOrCreate(['name' => 'create_task' , 'guard_name' => 'api']);
        Permission::firstOrCreate(['name' => 'show_task' , 'guard_name' => 'api']);
        Permission::firstOrCreate(['name' => 'update_task' , 'guard_name' => 'api']);
        Permission::firstOrCreate(['name' => 'delete_task' , 'guard_name' => 'api']);

        Permission::firstOrCreate(['name' => 'show_subtask' , 'guard_name' => 'api']);
        Permission::firstOrCreate(['name' => 'update_subtask' , 'guard_name' => 'api']);
        Permission::firstOrCreate(['name' => 'delete_subtask' , 'guard_name' => 'api']);

        Permission::firstOrCreate(['name' => 'update_note' , 'guard_name' => 'api']);
        Permission::firstOrCreate(['name' => 'delete_note' , 'guard_name' => 'api']);

        Permission::firstOrCreate(['name' => 'rename_group_name' , 'guard_name' => 'api']);
        Permission::firstOrCreate(['name' => 'add_group_member' , 'guard_name' => 'api']);

        Permission::firstOrCreate(['name' => 'update_status' , 'guard_name' => 'api']);

        $allPermissions = Permission::all()->pluck('name')->toArray();

        $adminRole->syncPermissions($allPermissions);
        

        $superAdmin->assignRole($superAdminRole);






    }
}

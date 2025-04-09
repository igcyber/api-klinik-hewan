<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['guard_name' => 'api', 'name' => 'register_role']);
        Permission::create(['guard_name' => 'api','name' => 'list_role']);
        Permission::create(['guard_name' => 'api','name' => 'edit_role']);
        Permission::create(['guard_name' => 'api','name' => 'delete_role']);

        Permission::create(['guard_name' => 'api', 'name' => 'register_veterinarian']);
        Permission::create(['guard_name' => 'api','name' => 'list_veterinarian']);
        Permission::create(['guard_name' => 'api','name' => 'edit_veterinarian']);
        Permission::create(['guard_name' => 'api','name' => 'delete_veterinarian']);
        Permission::create(['guard_name' => 'api','name' => 'profile_veterinarian']);

        Permission::create(['guard_name' => 'api', 'name' => 'register_pet']);
        Permission::create(['guard_name' => 'api','name' => 'list_pet']);
        Permission::create(['guard_name' => 'api','name' => 'edit_pet']);
        Permission::create(['guard_name' => 'api','name' => 'delete_pet']);
        Permission::create(['guard_name' => 'api','name' => 'profile_pet']);

        Permission::create(['guard_name' => 'api', 'name' => 'register_staff']);
        Permission::create(['guard_name' => 'api','name' => 'list_staff']);
        Permission::create(['guard_name' => 'api','name' => 'edit_staff']);
        Permission::create(['guard_name' => 'api','name' => 'delete_staff']);

        Permission::create(['guard_name' => 'api', 'name' => 'register_appointment']);
        Permission::create(['guard_name' => 'api','name' => 'list_appointment']);
        Permission::create(['guard_name' => 'api','name' => 'edit_appointment']);
        Permission::create(['guard_name' => 'api','name' => 'delete_appointment']);

        Permission::create(['guard_name' => 'api','name' => 'show_payment']);
        Permission::create(['guard_name' => 'api','name' => 'edit_payment']);

        Permission::create(['guard_name' => 'api','name' => 'calendar']);

        Permission::create(['guard_name' => 'api','name' => 'register_vaccination']);
        Permission::create(['guard_name' => 'api','name' => 'list_vaccination']);
        Permission::create(['guard_name' => 'api','name' => 'edit_vaccination']);
        Permission::create(['guard_name' => 'api','name' => 'delete_vaccination']);

        Permission::create(['guard_name' => 'api','name' => 'register_surgeries']);
        Permission::create(['guard_name' => 'api','name' => 'list_surgeries']);
        Permission::create(['guard_name' => 'api','name' => 'edit_surgeries']);
        Permission::create(['guard_name' => 'api','name' => 'delete_surgeries']);

        Permission::create(['guard_name' => 'api','name' => 'show_medical_records']);

        Permission::create(['guard_name' => 'api','name' => 'show_report_grafics']);

        // create roles and assign existing permissions
        $role3 = Role::create(['guard_name' => 'api','name' => 'super-admin']);

        $user = \App\Models\User::factory()->create([
            'name' => 'Super Admin User',
            'username' => 'super-admin',
            'role_id' => 1,
            'avatar' => null,
            'email' => 'superadmin@gmail.com',
            'password' => bcrypt('password')
        ]);
        $user->assignRole($role3);
    }
}

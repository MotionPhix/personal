<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    // Reset cached roles and permissions
    app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

    // Create permissions
    $permissions = [
      // User management
      'view users',
      'create users',
      'edit users',
      'delete users',
      'manage user roles',

      // Customer management
      'view customers',
      'create customers',
      'edit customers',
      'delete customers',
      'export customers',

      // Project management
      'view projects',
      'create projects',
      'edit projects',
      'delete projects',
      'publish projects',
      'manage project media',
      'export projects',

      // Logo/Download management
      'view downloads',
      'create downloads',
      'edit downloads',
      'delete downloads',
      'manage download files',

      // Subscriber management
      'view subscribers',
      'create subscribers',
      'edit subscribers',
      'delete subscribers',
      'export subscribers',

      // Dashboard and analytics
      'view dashboard',
      'view analytics',
      'view reports',
      'export reports',

      // System settings
      'manage settings',
      'manage roles',
      'manage permissions',
      'view system logs',
      'backup system',

      // Content management
      'manage content',
      'manage media library',
      'manage seo settings',

      // API access
      'access api',
      'manage api tokens',
    ];

    foreach ($permissions as $permission) {
      Permission::create(['name' => $permission]);
    }

    // Create roles and assign permissions

    // Super Admin - has all permissions
    $superAdmin = Role::create(['name' => 'super-admin']);
    $superAdmin->givePermissionTo(Permission::all());

    // Admin - has most permissions except system-critical ones
    $admin = Role::create(['name' => 'admin']);
    $admin->givePermissionTo([
      'view users',
      'create users',
      'edit users',
      'view customers',
      'create customers',
      'edit customers',
      'delete customers',
      'export customers',
      'view projects',
      'create projects',
      'edit projects',
      'delete projects',
      'publish projects',
      'manage project media',
      'export projects',
      'view downloads',
      'create downloads',
      'edit downloads',
      'delete downloads',
      'manage download files',
      'view subscribers',
      'create subscribers',
      'edit subscribers',
      'delete subscribers',
      'export subscribers',
      'view dashboard',
      'view analytics',
      'view reports',
      'export reports',
      'manage content',
      'manage media library',
      'manage seo settings',
      'access api',
    ]);

    // Manager - can manage content but not users or system settings
    $manager = Role::create(['name' => 'manager']);
    $manager->givePermissionTo([
      'view customers',
      'create customers',
      'edit customers',
      'export customers',
      'view projects',
      'create projects',
      'edit projects',
      'publish projects',
      'manage project media',
      'export projects',
      'view downloads',
      'create downloads',
      'edit downloads',
      'manage download files',
      'view subscribers',
      'create subscribers',
      'edit subscribers',
      'export subscribers',
      'view dashboard',
      'view analytics',
      'view reports',
      'manage content',
      'manage media library',
    ]);

    // Editor - can edit content but not delete or manage users
    $editor = Role::create(['name' => 'editor']);
    $editor->givePermissionTo([
      'view customers',
      'view projects',
      'create projects',
      'edit projects',
      'manage project media',
      'view downloads',
      'create downloads',
      'edit downloads',
      'view subscribers',
      'view dashboard',
      'manage content',
      'manage media library',
    ]);

    // Viewer - read-only access
    $viewer = Role::create(['name' => 'viewer']);
    $viewer->givePermissionTo([
      'view customers',
      'view projects',
      'view downloads',
      'view subscribers',
      'view dashboard',
      'view analytics',
      'view reports',
    ]);

    // Client - limited access for external clients
    $client = Role::create(['name' => 'client']);
    $client->givePermissionTo([
      'view projects',
      'view dashboard',
    ]);

    $this->command->info('Roles and permissions created successfully!');
    $this->command->info('Created roles: super-admin, admin, manager, editor, viewer, client');
    $this->command->info('Created ' . count($permissions) . ' permissions');
  }
}

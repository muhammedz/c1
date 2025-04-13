<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // İzinleri oluştur
        $permissions = [
            'user_management_access',
            'user_create',
            'user_edit',
            'user_delete',
            'user_show',
            
            'role_management_access',
            'role_create',
            'role_edit',
            'role_delete',
            'role_show',
            
            'page_management_access',
            'page_create',
            'page_edit',
            'page_delete',
            'page_show',
            
            'post_management_access',
            'post_create',
            'post_edit',
            'post_delete',
            'post_show',
            
            'category_management_access',
            'category_create',
            'category_edit',
            'category_delete',
            'category_show',
            
            'media_management_access',
            'media_create',
            'media_edit',
            'media_delete',
            'media_show',
            
            'menu_management_access',
            'menu_create',
            'menu_edit',
            'menu_delete',
            'menu_show',
            
            'setting_management_access',
            'setting_edit',
        ];
        
        foreach ($permissions as $permission) {
            Permission::create([
                'name' => $permission
            ]);
        }
        
        // Admin rolü oluştur
        $adminRole = Role::create(['name' => 'admin']);
        
        // Admin rolüne tüm izinleri ver
        $adminRole->givePermissionTo(Permission::all());
        
        // Editör rolü oluştur
        $editorRole = Role::create(['name' => 'editor']);
        
        // Editöre bazı izinleri ver
        $editorPermissions = [
            'page_management_access',
            'page_create',
            'page_edit',
            'page_show',
            
            'post_management_access',
            'post_create',
            'post_edit',
            'post_show',
            
            'category_management_access',
            'category_create',
            'category_edit',
            'category_show',
            
            'media_management_access',
            'media_create',
            'media_show',
        ];
        
        $editorRole->givePermissionTo($editorPermissions);
        
        // Admin kullanıcısı oluştur
        $adminUser = User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
            'is_active' => true,
        ]);
        
        // Admin kullanıcısına admin rolünü ata
        $adminUser->assignRole('admin');
    }
}

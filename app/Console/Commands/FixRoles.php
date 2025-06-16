<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class FixRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:roles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix missing roles and permissions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Rolleri kontrol ediliyor...');
        
        // Admin rolü kontrol et
        $adminRole = Role::where('name', 'admin')->first();
        if (!$adminRole) {
            $adminRole = Role::create(['name' => 'admin']);
            $this->info('Admin rolü oluşturuldu.');
        } else {
            $this->info('Admin rolü mevcut.');
        }
        
        // Editor rolü kontrol et
        $editorRole = Role::where('name', 'editor')->first();
        if (!$editorRole) {
            $editorRole = Role::create(['name' => 'editor']);
            $this->info('Editor rolü oluşturuldu.');
        } else {
            $this->info('Editor rolü mevcut.');
        }
        
        // Temel izinleri kontrol et
        $permissions = [
            'user_management_access',
            'role_management_access',
            'page_management_access',
            'post_management_access',
        ];
        
        foreach ($permissions as $permissionName) {
            $permission = Permission::where('name', $permissionName)->first();
            if (!$permission) {
                Permission::create(['name' => $permissionName]);
                $this->info("İzin oluşturuldu: {$permissionName}");
            }
        }
        
        // Admin rolüne tüm izinleri ver
        $adminRole->syncPermissions(Permission::all());
        
        // Rol ataması olmayan kullanıcıları kontrol et
        $usersWithoutRoles = User::doesntHave('roles')->get();
        
        if ($usersWithoutRoles->count() > 0) {
            $this->info("Rol ataması olmayan {$usersWithoutRoles->count()} kullanıcı bulundu.");
            
            foreach ($usersWithoutRoles as $user) {
                // İlk kullanıcıya admin rolü ver, diğerlerine editor
                if ($user->id === 1 || $user->email === 'admin@admin.com') {
                    $user->assignRole('admin');
                    $this->info("Admin rolü atandı: {$user->name} ({$user->email})");
                } else {
                    $user->assignRole('editor');
                    $this->info("Editor rolü atandı: {$user->name} ({$user->email})");
                }
            }
        }
        
        // Bozuk rol atamalarını temizle
        $this->info('Bozuk rol atamaları kontrol ediliyor...');
        
        // model_has_roles tablosundaki geçersiz rol ID'lerini temizle
        \DB::table('model_has_roles')
            ->whereNotIn('role_id', Role::pluck('id'))
            ->delete();
            
        $this->info('Bozuk rol atamaları temizlendi.');
        
        $this->info('Roller başarıyla düzeltildi!');
        
        // Mevcut rolleri listele
        $this->info('Mevcut roller:');
        foreach (Role::all() as $role) {
            $this->line("- {$role->name} (ID: {$role->id})");
        }
        
        return 0;
    }
}

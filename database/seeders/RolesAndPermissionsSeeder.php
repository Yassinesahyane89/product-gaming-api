<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();


        $editMyProfil = 'edit my profil';
        $editAllProfil = 'edit all profil';
        $deleteMyProfil = 'delete my profil';
        $deleteAllProfil = 'delete all profil';
        $viewMyprofil = 'view my profil';
        $viewAllprofil = 'view all profil';

        $addProduct = 'add product';
        $editAllProduct = 'edit All product';
        $editMyProduct = 'edit My product';
        $deleteAllProduct = 'delete All product';
        $deleteMyProduct = 'delete My product';

        $addCategory = 'add category';
        $editCategory = 'edit category';
        $deleteCategory = 'delete category';
        $viewCategory = 'view category';

        $addRole = 'add role';
        $editRole = 'edit role';
        $changeRoleUser = 'change role user';
        $viewRole = 'view role';

        Permission::create(['name' => $editMyProfil]);
        Permission::create(['name' => $editAllProfil]);
        Permission::create(['name' => $deleteMyProfil]);
        Permission::create(['name' => $deleteAllProfil]);
        Permission::create(['name' => $viewMyprofil]);
        Permission::create(['name' => $viewAllprofil]);

        Permission::create(['name' => $addProduct]);
        Permission::create(['name' => $editAllProduct]);
        Permission::create(['name' => $editMyProduct]);
        Permission::create(['name' => $deleteAllProduct]);
        Permission::create(['name' => $deleteMyProduct]);

        Permission::create(['name' => $addCategory]);
        Permission::create(['name' => $editCategory]);
        Permission::create(['name' => $deleteCategory]);
        Permission::create(['name' => $viewCategory]);

        Permission::create(['name' => $addRole]);
        Permission::create(['name' => $editRole]);
        Permission::create(['name' => $changeRoleUser]);
        Permission::create(['name' => $viewRole]);

        // Define roles available
        $admin = 'admin';
        $seller = 'seller';
        $user = 'user';

        Role::create(['name' => $admin])->givePermissionTo(Permission::all());

        Role::create(['name' => $seller])->givePermissionTo([
            $addProduct,
            $editMyProduct,
            $deleteMyProduct,
            $editMyProfil,
            $deleteMyProfil,
            $viewMyprofil,
        ]);

        Role::create(['name' => $user])->givePermissionTo([
            $editMyProfil,
            $deleteMyProfil,
            $viewMyprofil,
        ]);
    }
}

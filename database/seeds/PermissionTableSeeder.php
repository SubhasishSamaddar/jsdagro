<?php


use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;


class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $permissions = [

            'country-list',
            'country-create',
            'country-edit',
            'country-delete',
            'navigation-list',
            'navigation-create',
            'navigation-edit',
            'navigation-delete',
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',
            'state-list',
            'state-create',
            'state-edit',
            'state-delete',
            'user-list',
            'user-create',
            'user-edit',
            'user-delete',
            'email_template-list',
            'email_template-create',
            'email_template-edit',
            'email_template-delete',
            'category-list',
            'category-create',
            'category-edit',
            'category-delete',
            'product-list',
            'product-create',
            'product-edit',
            'product-delete',
			'order-list',
            'order-create',
            'order-edit',
            'order-delete',
			'swadesh_hut-list',
            'swadesh_hut-create',
            'swadesh_hut-edit',
            'swadesh_hut-delete',
			'package_location-list',
            'package_location-create',
            'package_location-edit',
            'package_location-delete',
            'package_inventory-list',
            'package_inventory-create',
            'package_inventory-edit',
            'package_inventory-delete'
        ];


        foreach ($permissions as $permission) {
             Permission::create(['name' => $permission]);
        }
    }
}

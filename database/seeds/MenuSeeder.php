<?php

use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Default admin sidebar
        $adminDefault = \App\Models\Menu::create([
            'id' => '386a3745-3c13-58c4-f6ac-c1962cabc9db',
            'parent_id' => '0',
            'menu_title' => 'Admin Menu',
            'slug' => 'admin',
            'icon' => 'fa fa-dashboard',
            'menu_order' => 0,
            'is_active' => 1,
            'is_default' => 1,
        ])->id;

        $dashboardMenu = \App\Models\Menu::create([
            'id' => (string)Uuid::generate(4),
            'parent_id' => $adminDefault,
            'menu_title' => 'Dashboard',
            'slug' => 'dashboard',
            'icon' => 'fa fa-dashboard',
            'url' => 'dashboard',
            'menu_order' => 0,
            'is_default' => 1,
            'is_active' => 1,
        ])->id;

        \App\Models\Menu::create([
            'id' => (string)Uuid::generate(4),
            'parent_id' => $adminDefault,
            'menu_title' => 'Menu management',
            'slug' => 'sidebar-roles',
            'url' => 'sidebars',
            'icon' => 'fa fa-user',
            'menu_order' => 1,
            'is_active' => 1,
            'is_default' => 1,
        ])->id;

        \App\Models\Menu::create([
            'id' => (string)Uuid::generate(4),
            'parent_id' => $adminDefault,
            'menu_title' => 'User management',
            'slug' => 'sidebar-users',
            'url' => 'users',
            'icon' => 'fa fa-user',
            'menu_order' => 2,
            'is_active' => 1,
            'is_default' => 1,
        ])->id;

        $menus = \App\Models\Menu::select()
            ->whereNotNull('slug')
            ->get();

        foreach ($menus as $menu) {
            // Add menu to owner
            \App\Models\MenuRole::create([
                'id' => (string)Uuid::generate(4),
                'role_id' => '2fba941d-8e41-40f0-829b-85087e1618d3',
                'menu_id' => $menu->id,
                'is_enabled' => 1
            ]);
            // Add menu to administrator
            \App\Models\MenuRole::create([
                'id' => (string)Uuid::generate(4),
                'role_id' => 'b2bb972c-80a9-4f35-9b6a-7ca56727a248',
                'menu_id' => $menu->id,
                'is_enabled' => 1
            ]);
        }
    }
}
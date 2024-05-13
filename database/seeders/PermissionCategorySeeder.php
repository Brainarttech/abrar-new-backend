<?php

namespace Database\Seeders;

use App\Models\PermissionCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PermissionCategory::create([
            'name'=>'Role',
        ]);
        PermissionCategory::create([
            'name'=>'Inventory',

        ]);
        PermissionCategory::create([
            'name'=>'Setting',

        ]);
        PermissionCategory::create([
            'name'=>'Permissions',

        ]);
        PermissionCategory::create([
            'name'=>'Departments',

        ]);
    }
}

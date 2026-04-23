<?php

namespace Database\Seeders;

use App\Models\RoleModel;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        RoleModel::query()->updateOrCreate(
            ['name' => 'user'],
            ['name' => 'user']
        );

        RoleModel::query()->updateOrCreate(
            ['name' => 'admin'],
            ['name' => 'admin']
        );

        RoleModel::query()->updateOrCreate(
            ['name' => 'superadmin'],
            ['name' => 'superadmin']
        );
    }
}

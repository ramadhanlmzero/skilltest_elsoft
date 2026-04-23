<?php

namespace Database\Seeders;

use App\Models\CompanyModel;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CompanyModel::query()->updateOrCreate(
            ['domain' => 'admin'],
            [
                'name' => 'Admin Company',
            ]
        );

        CompanyModel::query()->updateOrCreate(
            ['domain' => 'testcase'],
            [
                'name' => 'Testcase Company',
            ]
        );
    }
}

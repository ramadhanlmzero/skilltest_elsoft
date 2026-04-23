<?php

namespace Database\Seeders;

use App\Models\CompanyModel;
use App\Models\RoleModel;
use App\Models\UserModel;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminCompanyId = (string) CompanyModel::query()
            ->where('domain', 'admin')
            ->value('id');

        $testcaseCompanyId = (string) CompanyModel::query()
            ->where('domain', 'testcase')
            ->value('id');

        $adminRoleId = (string) RoleModel::query()
            ->where('name', 'admin')
            ->value('id');

        UserModel::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'username' => 'admin',
                'password' => bcrypt('admin123'),
                'company_id' => $adminCompanyId,
                'role_id' => $adminRoleId,
            ]
        );

        UserModel::updateOrCreate(
            ['email' => 'testcase@example.com'],
            [
                'name' => 'testcase',
                'username' => 'testcase',
                'password' => bcrypt('testcase123'),
                'company_id' => $testcaseCompanyId,
                'role_id' => $adminRoleId,
            ]
        );
    }
}

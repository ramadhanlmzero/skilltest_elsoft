<?php

namespace Database\Seeders;

use App\Models\AccountModel;
use App\Models\CompanyModel;
use App\Models\StockIssueStatusModel;
use Illuminate\Database\Seeder;

class StockIssueMasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StockIssueStatusModel::query()->updateOrCreate(
            ['name' => 'Entry'],
            ['name' => 'Entry']
        );

        $company = CompanyModel::query()->where('domain', 'testcase')->first();

        if (! $company) {
            return;
        }

        AccountModel::query()->updateOrCreate(
            [
                'company_id' => $company->id,
                'name' => 'Biaya Adm Bank - 800-01 - 800-01 (testcase)',
            ],
            [
                'company_id' => $company->id,
                'name' => 'Biaya Adm Bank - 800-01 - 800-01 (testcase)',
            ]
        );
    }
}

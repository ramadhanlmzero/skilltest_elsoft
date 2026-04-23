<?php

namespace Database\Seeders;

use App\Models\ItemAccountGroupModel;
use App\Models\ItemGroupModel;
use App\Models\ItemTypeModel;
use App\Models\ItemUnitModel;
use Illuminate\Database\Seeder;

class ItemMasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ItemTypeModel::query()->updateOrCreate(
            ['name' => 'Product'],
            ['name' => 'Product']
        );

        ItemGroupModel::query()->updateOrCreate(
            ['name' => 'PRODUCT LAIN - LAIN - PI'],
            ['name' => 'PRODUCT LAIN - LAIN - PI']
        );

        ItemAccountGroupModel::query()->updateOrCreate(
            ['name' => 'DEFAULT - DEF (TESTCASE)'],
            ['name' => 'DEFAULT - DEF (TESTCASE)']
        );

        ItemUnitModel::query()->updateOrCreate(
            ['name' => 'PCS'],
            ['name' => 'PCS']
        );
    }
}

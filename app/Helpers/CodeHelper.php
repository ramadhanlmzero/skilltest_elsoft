<?php

namespace App\Helpers;

use App\Models\ItemModel;
use App\Models\StockIssueModel;
use Illuminate\Support\Str;

class CodeHelper
{
    public static function item(?int $length = 8): string
    {
        $prefix = 'ITM';
        $randomstring = Str::upper(Str::random($length));
        $code = "$prefix-$randomstring";

        if (ItemModel::where('code', $code)->exists()) {
            return self::item($length);
        }

        return $code;
    }

    public static function stockIssue(int $length = 8): string
    {
        $prefix = 'STK';
        $randomstring = Str::upper(Str::random($length));
        $code = "$prefix-$randomstring";

        if (StockIssueModel::where('code', $code)->exists()) {
            return self::stockIssue($length);
        }

        return $code;
    }
}

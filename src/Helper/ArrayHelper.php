<?php


namespace App\Helper;

class ArrayHelper
{
    public static function mergeUnique(array ...$arrays): array
    {
        $merged = array_merge(...$arrays);
        $unique = array_unique($merged);
        return $unique;
    }
}

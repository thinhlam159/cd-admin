<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\ToCollection;

class OrderImport implements ToArray
{
    /**
    * @param array $array
    */
    public function array(array $array)
    {
//        $orderExcelTemplate = [];
//        foreach ($array as $item) {
//            $orderExcelTemplate[] = $item;
//        }
//
//        return $orderExcelTemplate;
    }
}

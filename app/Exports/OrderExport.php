<?php

namespace App\Exports;

use App\Bundle\ProductBundle\Application\OrderExportPostResult;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;

/**
 *
 */
class OrderExport implements FromArray
{
    /**
     * @var OrderExportPostResult
     */
    private OrderExportPostResult $orderExportPostResult;

    /**
     * @param OrderExportPostResult $orderExportPostResult
     */
    public function __construct(OrderExportPostResult $orderExportPostResult)
    {
        $this->orderExportPostResult = $orderExportPostResult;
    }

    /**
     * @return \int[][]
     */
    public function array(): array
    {
        $customerName = $this->orderExportPostResult->customerName;

        return [
            [1, 2, 3],
            [4, 5, 6]
        ];
    }

    public function startCell(): string
    {
        return 'B2';
    }
}

<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithDefaultStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Style;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 *
 */
class OrderExport implements FromArray, WithStyles, ShouldAutoSize, WithEvents, WithDefaultStyles, WithColumnWidths, WithColumnFormatting
{
    /**
     * @var array
     */
    private array $orderExportPostResult;

    /**
     * @var int
     */
    private int $orderInfoRowsCount;

    /**
     * @param array $orderExportPostResult
     * @param int $orderInfoRowsCount
     */
    public function __construct(array $orderExportPostResult, int $orderInfoRowsCount)
    {
        $this->orderExportPostResult = $orderExportPostResult;
        $this->orderInfoRowsCount = $orderInfoRowsCount;
    }

    /**
     * @return int[][]
     */
    public function array(): array
    {
        return $this->orderExportPostResult;
    }

    public function defaultStyles(Style $defaultStyle)
    {
//        // Or return the styles array
        $defaultStyle->getFont()->setSize(13)->setName('Cambria');
        return [
            'fill' => [
            ],
        ];
    }
    public function styles(Worksheet $sheet)
    {
        $lastInfoRow = 11 + $this->orderInfoRowsCount;
        $totalRow = $this->orderInfoRowsCount + 12;
        $moneyByStringRow = $this->orderInfoRowsCount + 13;
        $dateRow = $this->orderInfoRowsCount + 15;
        $lastRow = $this->orderInfoRowsCount + 16;
        $sheet->getStyle(11)->getAlignment()->setHorizontal('center');
        $sheet->getStyle("A5")->getAlignment()->setVertical('top')->setWrapText(true);
        for ($i = 1; $i <= $lastRow; $i++) {
            $sheet->getRowDimension($i)->setRowHeight(18);
        }
        $sheet->getStyle(2)->getAlignment()->setHorizontal('center');
        $sheet->getStyle(8)->getAlignment()->setHorizontal('center');

        return [
            // Style the first row as bold text.
            1 => ['font' => [
                'bold' => true,
                'size' => 14
            ]],
            2 => [
                'font' => [
                    'bold' => true,
                    'size' => 14,
                ],
            ],
            5 => [
                'font' => [
                    'bold' => true,
                    'italic' => true
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
            8 => ['font' => [
                'bold' => true,
                'size' => 20,
            ]],
            11 => ['font' => [
                'bold' => true,
            ]],

            // Styling a specific cell by coordinate.
            'A2' => [
                'font' => [
                    'italic' => true
                ]
            ],
            "A11:F$totalRow" => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => '000000'],
                    ],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
            ],
            $totalRow => [
                'font' => [
                    'bold' => true,
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
            ],
            $moneyByStringRow => [
                'font' => [
                    'italic' => true
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
            ],
            $dateRow => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
            ],
            $lastRow => [
                'font' => [
                    'bold' => true
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
            ]
        ];
    }

    public function registerEvents(): array
    {
        $lastInfoRow = 11 + $this->orderInfoRowsCount;
        $totalRow = $this->orderInfoRowsCount + 12;
        $moneyByStringRow = $this->orderInfoRowsCount + 13;
        $dateRow = $this->orderInfoRowsCount + 15;
        $lastRow = $this->orderInfoRowsCount + 16;

        return [
//            BeforeExport::class  => function(BeforeExport $event) {
//                $event->writer->setCreator('Patrick');
//            },
            AfterSheet::class => function(AfterSheet $event) use ($totalRow, $moneyByStringRow, $dateRow ,$lastRow) {
                $event->sheet->mergeCells("A8:F8");
                $event->sheet->mergeCells("A1:F1");
                $event->sheet->mergeCells("A2:F2");
                $event->sheet->mergeCells("A3:F3");
                $event->sheet->mergeCells("A4:F4");
                $event->sheet->mergeCells("A5:F6");
                $event->sheet->mergeCells("A$totalRow:C$totalRow");
                $event->sheet->mergeCells("A$moneyByStringRow:B$moneyByStringRow");
                $event->sheet->mergeCells("E$dateRow:F$dateRow");
                $event->sheet->mergeCells("E$lastRow:F$lastRow");
                $event->sheet->getRowDimension(1)->setRowHeight(20);
                $event->sheet->getRowDimension(2)->setRowHeight(18);
                $event->sheet->getRowDimension(5)->setRowHeight(18);
                $event->sheet->getRowDimension(8)->setRowHeight(25);
            },
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 4,
            'B' => 18,
            'C' => 8,
            'D' => 10,
            'E' => 12,
            'F' => 20,
        ];
    }

    public function columnFormats(): array
    {
        return [
            'E' => '#,##0',
            'F' => '#,##0',
        ];
    }
}

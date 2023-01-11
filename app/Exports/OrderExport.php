<?php

namespace App\Exports;

use App\Bundle\ProductBundle\Application\OrderExportPostResult;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Style;
use PhpOffice\PhpSpreadsheet\Style\Color;
use Maatwebsite\Excel\Concerns\WithDefaultStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Cell;

/**
 *
 */
class OrderExport implements FromArray, WithStyles, ShouldAutoSize, WithEvents, WithDefaultStyles, WithColumnWidths
{
    /**
     * @var array
     */
    private array $orderExportPostResult;

    /**
     * @param array $orderExportPostResult
     */
    public function __construct(array $orderExportPostResult)
    {
        $this->orderExportPostResult = $orderExportPostResult;
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
//        // Configure the default styles
////        return $defaultStyle->getFill()->setFillType(Fill::FILL_SOLID);
//
//        // Or return the styles array
        $defaultStyle->getFont()->setSize(13);
        return [
            'fill' => [
//                'fillType'   => Fill::FILL_GRADIENT_LINEAR,
//                'startColor' => ['argb' => Color::COLOR_RED],

            ],
        ];
    }
    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle(11)->getAlignment()->setHorizontal('center');
        $sheet->getStyle("A5")->getAlignment()->setVertical('top')->setWrapText(true);
        $sheet->getRowDimension(5)->setRowHeight(30);
        return [
            // Style the first row as bold text.
            1 => ['font' => [
                'bold' => true,
                'size' => 14
            ]],
            5 => ['font' => [
                'bold' => true,
                'size' => 14,
                'align' => 'top'
            ]],
            8 => ['font' => [
                'bold' => true
            ]],
            11 => ['font' => [
                'bold' => true,
                'alignment' => 'center'
            ]],

            // Styling a specific cell by coordinate.
//            'B2' => ['font' => ['italic' => true]],

            // Styling an entire column.
//            'C'  => ['font' => ['size' => 16]],
        ];
    }

    public function registerEvents(): array
    {
        return [
//            BeforeExport::class  => function(BeforeExport $event) {
//                $event->writer->setCreator('Patrick');
//            },
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->mergeCells("A8:F8");
                $event->sheet->mergeCells("A1:G1");
                $event->sheet->mergeCells("A2:G2");
                $event->sheet->mergeCells("A3:G3");
                $event->sheet->mergeCells("A4:G4");
                $event->sheet->mergeCells("A5:G6");
                $event->sheet->getRowDimension(1)->setRowHeight(20);
//                $event->sheet->mergeCells("A5:A6");
            },
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 4,
            'B' => 20,
            'C' => 8,
            'D' => 12,
            'E' => 16,
            'F' => 18,
        ];
    }
}

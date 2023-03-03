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
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Style;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DebtCustomerExport implements FromArray, WithStyles, ShouldAutoSize, WithEvents, WithDefaultStyles, WithColumnWidths, WithColumnFormatting
{
    /**
     * @var array
     */
    private array $debtCustomerExportResult;

    /**
     * @var int
     */
    private int $countDebtResults;

    /**
     * @param array $debtCustomerExportResult
     * @param int $countDebtResults
     */
    public function __construct(array $debtCustomerExportResult, int $countDebtResults)
    {
        $this->debtCustomerExportResult = $debtCustomerExportResult;
        $this->countDebtResults = $countDebtResults;
    }

    /**
     * @return int[][]
     */
    public function array(): array
    {
        return $this->debtCustomerExportResult;
    }

    public function defaultStyles(Style $defaultStyle)
    {
        $defaultStyle->getFont()->setSize(13)->setName('Cambria');
        return [
            'fill' => [
            ],
        ];
    }
    public function styles(Worksheet $sheet)
    {

        $totalDebtRow = $this->countDebtResults + 9;
        $totalPaymentRow = $this->countDebtResults + 10;
        $restDebtRow = $this->countDebtResults + 11;
        $lastRow = $this->countDebtResults + 8;
        $sheet->getStyle(4)->getAlignment()->setHorizontal('center');
        $sheet->getStyle(7)->getAlignment()->setHorizontal('center');
        $sheet->getStyle("A$lastRow:J$lastRow")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('59a57f');

        for ($i = 1; $i <= $lastRow; $i++) {
            $sheet->getRowDimension($i)->setRowHeight(18);
        }

        return [
            // Style the first row as bold text.
            1 => ['font' => [
                'bold' => true,
                'size' => 14
            ]],
            5 => [
                'font' => [
                    'bold' => true,
                    'italic' => true
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
            4 => [
                'font' => [
                    'bold' => true,
                    'size' => 16,
                ],
            ],
            7 => [
                'font' => [
                    'bold' => true,
                ],
            ],

            // Styling a specific cell by coordinate.
            "A7:J$restDebtRow" => [
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
            $totalDebtRow => [
                'font' => [
                    'bold' => true,
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
            ],
            $totalPaymentRow => [
                'font' => [
                    'bold' => true,
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
            ],
            $restDebtRow=> [
                'font' => [
                    'bold' => true,
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
            ],
        ];
    }

    public function registerEvents(): array
    {
        $totalDebtRow = $this->countDebtResults + 9;
        $totalPaymentRow = $this->countDebtResults + 10;
        $restDebtRow = $this->countDebtResults + 11;

        return [
            AfterSheet::class => function(AfterSheet $event) use ($totalDebtRow, $totalPaymentRow, $restDebtRow) {
                $event->sheet->mergeCells("A1:F1");
                $event->sheet->mergeCells("A2:D2");
                $event->sheet->mergeCells("A3:F3");
                $event->sheet->mergeCells("A4:E4");
                $event->sheet->mergeCells("A5:E5");
                $event->sheet->mergeCells("A$totalDebtRow:D$totalDebtRow");
                $event->sheet->mergeCells("A$totalPaymentRow:D$totalPaymentRow");
                $event->sheet->mergeCells("A$restDebtRow:D$restDebtRow");

                $event->sheet->getRowDimension(1)->setRowHeight(20);
                $event->sheet->getRowDimension(4)->setRowHeight(20);
                $event->sheet->getRowDimension($totalDebtRow)->setRowHeight(20);
                $event->sheet->getRowDimension($totalPaymentRow)->setRowHeight(20);
                $event->sheet->getRowDimension($restDebtRow)->setRowHeight(20);
            },
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 4,
            'B' => 15,
            'C' => 15,
            'D' => 15,
            'E' => 15,
            'F' => 15,
            'G' => 15,
            'H' => 15,
            'I' => 15,
            'J' => 15,
        ];
    }

    public function columnFormats(): array
    {
        return [
            'C' => '#,##0',
            'D' => '#,##0',
            'E' => '#,##0',
            'F' => '#,##0',
            'G' => '#,##0',
            'H' => '#,##0',
            'I' => '#,##0',
        ];
    }
}

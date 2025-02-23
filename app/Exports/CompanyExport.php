<?php

namespace App\Exports;

use App\Models\User;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class CompanyExport implements FromView, WithEvents, WithHeadingRow
{
    /**
     * @return \Illuminate\Support\Collection
     */

    public function __construct(string $keyword)
    {
        $this->keyword = $keyword;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $keywords = $this->keyword;
                $count = User::when($keywords, function ($query) use($keywords) {
                    $query->where('name', 'like', "%$keywords%")
                        ->orWhere('email', 'like', "%$keywords%")
                        ->orWhere('username', 'LIKE', "%$keywords%");
                })->whereHas('roles', function ($query) {
                    $query->where('id', 3);
                })->count();

                $count++; //them header
                $allColumn = ['A', 'B', 'C', 'D', 'E'];
                //set font header
                $cellRange = 'A1:F1'; // All headers

                $styleArray = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
                    ],
                    'font' => [
                        'name' => 'Times New Roman (Headings)',
                        'size' => 12,
                    ]
                ];

                foreach ($allColumn as $col) {
                    $event->sheet->getStyle($col . "1:$col$count")->applyFromArray($styleArray)->getAlignment()
                        ->setWrapText(true);
                }

                // set columns to autosize
                for ($i = 1; $i <= 9; $i++) {
                    $column = Coordinate::stringFromColumnIndex($i);
                    $event->sheet->getColumnDimension($column)->setAutoSize(false);
                }


                // page formatting (orientation and size)
                $event->sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
                $event->sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_LETTER);

                $event->sheet->getDelegate()->getStyle('A1:M3')->getAlignment()->setWrapText(true);
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(false);
                $event->sheet->getStyle($cellRange)->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getStyle($cellRange)->getAlignment()
                    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                $event->sheet->getDelegate()->getColumnDimension("A")->setWidth(5);
                $event->sheet->getDelegate()->getColumnDimension("B")->setWidth(30);
                $event->sheet->getDelegate()->getColumnDimension("C")->setWidth(30);
                $event->sheet->getDelegate()->getColumnDimension("D")->setWidth(30);
                $event->sheet->getDelegate()->getColumnDimension("E")->setWidth(30);



                $event->sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
            },
        ];
    }

    public function drawings()
    {
        $drawing = new Drawing();

        return $drawing;
    }


    public function view(): View
    {
        $keywords = $this->keyword;

        $data = User::when($keywords, function ($query) use($keywords) {
            $query->where('name', 'like', "%$keywords%")
                ->orWhere('email', 'like', "%$keywords%")
                ->orWhere('username', 'LIKE', "%$keywords%");
        })->whereHas('roles', function ($query) {
            $query->where('id', 3);
        })->get();

        return view('admin.users.excel', ['data' => $data]);
    }
}

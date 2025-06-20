<?php

namespace App\Exports;

use App\Models\Category;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class CategoryTemplateExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize, WithColumnWidths
{
    public function collection()
    {
        return Category::select('id', 'name_en')
            ->where('active', 1)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'category_name' => $item->name_en,
                    'selected' => '',
                ];
            });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Category',
            'Selected',
        ];
    }

    // ✅ Kẻ border cho toàn bộ vùng dữ liệu
    public function styles(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow();
        $sheet->getStyle('A1:C' . $highestRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ]);

        // Bôi đậm dòng tiêu đề
        $sheet->getStyle('A1:C1')->getFont()->setBold(true);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 10, // id
            'B' => 40, // category_name
            'C' => 10, // selected
        ];
    }
}


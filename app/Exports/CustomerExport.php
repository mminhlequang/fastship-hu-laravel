<?php

namespace App\Exports;

use App\Models\Customer;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class CustomerExport implements FromView, WithEvents, WithHeadingRow
{
    /**
     * @return \Illuminate\Support\Collection
     */

    public function __construct(string $keywords, string $from, string $to, int $promotionId, int $active, int $province_id, int $district_id, int $ward_id, int $oldId)
    {
        $this->keywords = $keywords;
        $this->from = $from;
        $this->to = $to;
        $this->promotionId = $promotionId;
        $this->active = $active;
        $this->province_id = $province_id;
        $this->district_id = $district_id;
        $this->ward_id = $ward_id;
        $this->oldId = $oldId;

    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $keywords = $this->keywords;
                $from = $this->from ?? '';
                $to = $this->to ?? '';
                $promotionId = $this->promotionId ?? 0;
                $active = $this->active ?? '';
                $province_id = $this->province_id ?? 0;
                $district_id = $this->district_id ?? 0;
                $ward_id = $this->ward_id ?? 0;
                $oldId = $this->oldId ?? 0;

                $ids = \DB::table('promotions')->where('creator_id', \Auth::id())->pluck('id')->toArray();

                $count = Customer::when($keywords != '', function ($query) use($keywords) {
                    $query->where('name', 'like', "%$keywords%")
                        ->orWhere('email', 'like', "%$keywords%")
                        ->orWhere('phone', 'like', "%$keywords%");
                })->when($from != '' && $to != '', function ($query) use ($from, $to) {
                    $query->whereDate('created_at', '>=', $from)->whereDate('created_at', '<=', $to);
                })->when($promotionId != 0, function ($query) use ($promotionId) {
                    $query->where('promotion_id', $promotionId);
                })->when($active != '', function ($query) use ($active) {
                    $query->where('active', $active);
                })->when($province_id != 0, function ($query) use ($province_id) {
                    $query->where('province_id', $province_id);
                })->when($district_id != 0, function ($query) use ($district_id) {
                    $query->where('district_id', $district_id);
                })->when($ward_id != 0, function ($query) use ($ward_id) {
                    $query->where('ward_id', $ward_id);
                })->when($oldId != 0, function ($query) use ($oldId) {
                    $old = \DB::table('olds')->where('id', $oldId)->select(['start_old', 'end_old'])->first();
                    $startOld = Carbon::now()->subYears($old->end_old)->toDateString();
                    $endOld = Carbon::now()->subYears($old->start_old)->toDateString();
                    $query->whereDate('birthday', '>=', $startOld)
                        ->whereDate('birthday', '<=', $endOld);
                });

                if(\Auth::user()->isAdminCompany())
                    $count = $count->count();
                else
                    $count = $count->whereIn('promotion_id', $ids)->count();


                $count++; //them header
                $allColumn = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K'];
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
                for ($i = 1; $i <= 8; $i++) {
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
                $event->sheet->getDelegate()->getColumnDimension("C")->setWidth(35);
                $event->sheet->getDelegate()->getColumnDimension("D")->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension("E")->setWidth(25);
                $event->sheet->getDelegate()->getColumnDimension("F")->setWidth(45);
                $event->sheet->getDelegate()->getColumnDimension("G")->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension("H")->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension("I")->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension("J")->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension("K")->setWidth(30);


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
        $keywords = $this->keywords;
        $from = $this->from ?? '';
        $to = $this->to ?? '';
        $promotionId = $this->promotionId ?? 0;
        $active = $this->active ?? '';
        $province_id = $this->province_id ?? 0;
        $district_id = $this->district_id ?? 0;
        $ward_id = $this->ward_id ?? 0;
        $oldId = $this->oldId ?? 0;

        $ids = \DB::table('promotions')->where('creator_id', \Auth::id())->pluck('id')->toArray();

        $data = Customer::when($keywords != '', function ($query) use($keywords) {
            $query->where('name', 'like', "%$keywords%")
                ->orWhere('email', 'like', "%$keywords%")
                ->orWhere('phone', 'like', "%$keywords%");
        })->when($from != '' && $to != '', function ($query) use ($from, $to) {
            $query->whereDate('created_at', '>=', $from)->whereDate('created_at', '<=', $to);
        })->when($promotionId != 0, function ($query) use ($promotionId) {
            $query->where('promotion_id', $promotionId);
        })->when($active != '', function ($query) use ($active) {
            $query->where('active', $active);
        })->when($province_id != 0, function ($query) use ($province_id) {
            $query->where('province_id', $province_id);
        })->when($district_id != 0, function ($query) use ($district_id) {
            $query->where('district_id', $district_id);
        })->when($ward_id != 0, function ($query) use ($ward_id) {
            $query->where('ward_id', $ward_id);
        })->when($oldId != 0, function ($query) use ($oldId) {
            $old = \DB::table('olds')->where('id', $oldId)->select(['start_old', 'end_old'])->first();
            $startOld = Carbon::now()->subYears($old->end_old)->toDateString();
            $endOld = Carbon::now()->subYears($old->start_old)->toDateString();
            $query->whereDate('birthday', '>=', $startOld)
                ->whereDate('birthday', '<=', $endOld);
        });

        if(\Auth::user()->isAdminCompany())
            $data = $data->orderByDesc('updated_at')->get();
        else
            $data = $data->whereIn('promotion_id', $ids)->orderByDesc('updated_at')->get();

        return view('admin.customers.excel', ['data' => $data]);
    }
}

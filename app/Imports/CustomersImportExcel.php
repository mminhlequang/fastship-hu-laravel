<?php

namespace App\Imports;



use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;


class CustomersImportExcel implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading, WithValidation, SkipsOnFailure, SkipsOnError
{
    use Importable, SkipsFailures, SkipsErrors;

    private $rows = 0;

    private $ids;

    public function __construct(int $ids)
    {
        $this->ids = $ids;
    }

    public function rules(): array
    {
        $promotionId = $this->ids;
        return [
            '*.ho_ten' => 'required',
            '*.email' => ['required', 'email', function ($attribute, $value, $onFailure) use($promotionId) {
                $id = \DB::table('customers')->where([['email', $value], ['promotion_id', $promotionId]])->value('id');
                if ($id != null) {
                    $onFailure('Email đã đăng ký chương trình');
                }
            }],
            '*.dien_thoai' => 'required',

        ];

    }

    public function customValidationMessages()
    {
        return [
            'ho_ten.required' => 'Họ tên không được bỏ trống',
            'email.required' => 'Email không được bỏ trống',
            'dien_thoai.required' => 'Email không được bỏ trống',
            'email.email' => 'Email không đúng định dạng',
        ];
    }

    public function transformDate($date)
    {
        try {
            $format = 'Y-m-d';
            return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($date))->format($format);
//            return Carbon::createFromFormat("d/m/Y", $date)->format($format);
        } catch (\ErrorException $e) {
            return Carbon::createFromFormat("d/m/Y", $date)->format($format);
        }
    }

    public function model(array $rows)
    {
        ++$this->rows;
        $promotionId = $this->ids;

        \DB::table('customers')->insert([
            'name' => $rows['ho_ten'],
            'email' => $rows['email'],
            'phone' => $rows['dien_thoai'],
            'address' => $rows['dia_chi'],
            'province_id' => \DB::table('provinces')->where('name', 'LIKE', $rows['tinh_thanh_pho'])->value('id'),
            'district_id' => \DB::table('districts')->where('name', 'LIKE', $rows['quan_huyen'])->value('id'),
            'ward_id' => \DB::table('wards')->where('name', 'LIKE', $rows['phuong_xa'])->value('id'),
            'birthday' => isset($rows['ngay_sinh']) ? $this->transformDate($rows['ngay_sinh']) : null,
            'active' => 0,
            'promotion_id' => $promotionId
        ]);

    }

    public function getRowCount(): int
    {
        return $this->rows;

    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function chunkSize(): int
    {
        return 5000;
    }
}

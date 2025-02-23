<?php

namespace App\Imports;


use App\Mail\SendEmailQueue;
use App\Models\Promotion;
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
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class CustomersImport implements ToCollection, WithHeadingRow, WithBatchInserts, WithChunkReading, WithValidation, SkipsOnFailure, SkipsOnError
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
        return [
            '*.ho_ten' => 'required',
            '*.email' => 'required|email',

        ];

    }

    public function customValidationMessages()
    {
        return [
            'ho_ten.required' => 'Họ tên không được bỏ trống',
            'email.required' => 'Email không được bỏ trống',
            'email.email' => 'Email không đúng định dạng',
        ];
    }

    public function collection(Collection $rows)
    {
        ++$this->rows;
        $emails = [];
        foreach ($rows as $row)
            $emails[] = $row['email'];
        $ids = $this->ids;
        $promotion = Promotion::where('id', $ids)->first();
        //Gửi mail cho khách
        $content = [
            'title' => $promotion->name,
            'data' => $promotion,
            'markdown' => 'mail.promotion'
        ];
        \Mail::to($emails)
            ->queue(new SendEmailQueue($content));

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

<?php

namespace App\Http\Controllers\Admin;

use App\Exports\CustomerExport;
use App\Exports\CompanyExport;
use App\Http\Controllers\Controller;
use App\Imports\CustomersImport;
use App\Imports\CustomersImportExcel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExcelController extends Controller
{
    public function exportCustomer(Request $request){
        ini_set("memory_limit", '2048M');
        ini_set('max_execution_time', 180);

        $keywords = $request->keyword ?? "";
        $from = $request->from ?? "";
        $to = $request->to ?? "";
        $promotionId = $request->promotion_id ?? 0;
        $active = $request->active ?? 0;
        $province_id = $request->province_id ?? 0;
        $district_id = $request->district_id ?? 0;
        $ward_id = $request->ward_id ?? 0;
        $oldId = $request->old_id ?? 0;
        return Excel::download(new CustomerExport($keywords, $from, $to, $promotionId, $active, intval($province_id), intval($district_id), intval($ward_id), intval($oldId)), 'khach-hang-' . Carbon::now() . ".xlsx");
    }

    public function exportUser(Request $request){
        ini_set("memory_limit", '2048M');
        ini_set('max_execution_time', 180);
        return Excel::download(new CompanyExport($request->keyword ?? ""), 'company-' . Carbon::now() . ".xlsx");
    }

    public function importCustomer(Request $request)
    {
        $this->validate($request, [
            'file' => 'required'
        ]);
        $file = $request->file('file')->store('import');
        $id = $request->id;
        $import = new CustomersImport($id);
        $import->import($file);
        try {
            if ($import->failures()->isNotEmpty()) {
                $totalSuccess = $import->getRowCount();
                $count = $import->failures()->count();
                $sum = $count + $totalSuccess;
                if ($totalSuccess == 0) {
                    toastr()->warning(__('Vui lòng xem lại tệp tin nhập vào có ' . $count . ' lỗi trên tổng số ' . $sum . ''));
                    return back()->withFailures($import->failures());
                }
                toastr()->info(__(' Nhập dữ liệu được ' . $totalSuccess . ' trên tổng  ' . $sum . '! , trong đó có ' . $count . ' bị lỗi'));
                return back()->withFailures($import->failures());
            }
            toastr()->success(__('Gửi mail chương trình thành công !'));
            return redirect('admin/customers');
        } catch (\Exception $e) {
            return redirect('admin/customers')->withErrors($e->getMessage());
        }
    }


    public function importExcelCustomer(Request $request)
    {
        $this->validate($request, [
            'file' => 'required'
        ]);
        $file = $request->file('file')->store('import');
        $id = $request->id;
        $import = new CustomersImportExcel($id);
        $import->import($file);
        try {
            if ($import->failures()->isNotEmpty()) {
                $totalSuccess = $import->getRowCount();
                $count = $import->failures()->count();
                $sum = $count + $totalSuccess;
                if ($totalSuccess == 0) {
                    toastr()->warning(__('Vui lòng xem lại tệp tin nhập vào có ' . $count . ' lỗi trên tổng số ' . $sum . ''));
                    return back()->withFailures($import->failures());
                }
                toastr()->info(__(' Nhập dữ liệu được ' . $totalSuccess . ' trên tổng  ' . $sum . '! , trong đó có ' . $count . ' bị lỗi'));
                return back()->withFailures($import->failures());
            }
            toastr()->success(__('Import thành công !'));
            return redirect('admin/customers');
        } catch (\Exception $e) {
            return redirect('admin/customers')->withErrors($e->getMessage());
        }
    }
}

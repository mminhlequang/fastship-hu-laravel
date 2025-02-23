<?php
namespace App\Helper;
class DateHelper
{
    public static function getListDayInMonth()
    {
        $arrDay = [];
        $month = date('m');
        $year = date('Y');
        // lấy tất cả ngày trong tháng
        for ($day = 1; $day <= 31; $day++)
        {
            $time = mktime('12', '0', '0', $month, $day, $year);
            if (date('m', $time) == $month)
                $arrDay[] = date('Y-m-d', $time);
        }
        return $arrDay;
    }
    public static function getListMonthInYear()
    {
        // $arrMonth = [];
        // for ($m=1; $m<=12; $m++) {
        //     $month = date('F', mktime(0,0,0,$m, 1, date('Y')));
        //      $arrMonth[] = date('Y-m-d', $month);
        //     }


        $arrMonth = [];
        $year = date('Y');
        // lấy tất cả ngày trong tháng
        for ($month = 1; $month <= 13; $month++)
        {
            $time = mktime('0','0','0', $month,0, $year );
            if (date('Y', $time) == $year)
                $arrMonth[] = date('Y-m-d', $time);
        }
        return $arrMonth;
    }
}
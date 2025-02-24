<?php

namespace App\Models;


use Modules\Booking\Events\BookingEvent;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use Symfony\Component\Debug\ExceptionHandler;

class Booking extends Model
{
    use Sortable;

    public $sortable = [
        'id',
        'updated_at',
        'approved',
        'created_at',
    ];
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'bookings';

    /**
     * The database primary key value.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['code', 'note', 'cancel_note', 'discount_id', 'invoice', 'customer_id', 'address_id', 'approve_id', 'affiliate_id', 'payment_type', 'creator_id', 'total_price', 'payment_method'];


    public function address()
    {
        return $this->belongsTo('App\Models\AddressDelivery');
    }

    public function creator()
    {
        return $this->belongsTo('App\Models\User', 'creator_id');
    }

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer');
    }

    public function detail()
    {
        return $this->belongsToMany('App\Models\BookingDetail', 'booking_detail')->withPivot('price', 'quantity');
    }

    public function approve()
    {
        return $this->belongsTo('App\Models\Approve', 'approve_id');
    }

    public function bookingItem()
    {
        return $this->belongsToMany('App\Models\Product', 'booking_items')->withPivot('quantity');
    }

    public function discount()
    {
        return $this->belongsTo('App\Models\Discount');
    }

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            if (\Auth::check()) {
                $model->creator_id = \Auth::id();
            }
            $model->code = self::getCodeUnique();
        });
        self::deleted(function ($modal) {
            // $modal->product_properties()->delete();
        });
    }

    public function getTotalNumberAttribute()
    {
        return $this->adult_number + $this->child_number;
    }

    /**
     * Get users receive notify - lay danh sach user nhan thong bao notify cua 1 booking
     * @param $userAction - User create event (create, update, cancel booking)
     *
     * @return array
     */
    public function usersReceiveNotify($userAction = null)
    {
        //Tour or Journey modal
        $detail = optional($this->detail)->bookingable;
        if (!$detail) return null;
        //Người đc phân quyền
        $userManager = $detail ? $detail->managers->pluck('id')->toArray() : [];
        //Người tạo booking
        $userManager[] = $this->creator_id;
        //remove user id trùng
        $userManager = array_unique($userManager);
        if (!empty($userAction)) {
            //remove user tạo nên notify này (thêm, cập nhật hoặc hủy)
            if (($key = array_search($userAction->id, $userManager)) !== false) {
                unset($userManager[$key]);
            }
        }
        return User::whereIn('id', $userManager)->get();
    }

    public static function notifyBookingTmp()
    {
        //get tmp booking - updated_at <= now() - 5 minutes (get minutes by company_settings table)
        // select bookings.* from bookings
        // left join users on users.id = bookings.creator_id
        // left join company_settings on (company_settings.company_id = users.company_id and company_settings.key = 'minutes_repeat_notify_booking_tmp')
        // where bookings.approved = 5 and bookings.updated_at <= DATE_SUB(NOW(),INTERVAL company_settings.value MINUTE) group by bookings.id
        $keyMinutesRepeat = "minutes_repeat_notify_booking_tmp";
        $keyIsSendNotify = "is_send_notify_booking_tmp";
        Booking::select(\DB::raw('bookings.*, MAX(IFNULL(company_settings.`value`,' . CompanySetting::$key[$keyMinutesRepeat] . ')) as minutes_repeat'))
            ->leftJoin('users', 'users.id', '=', 'bookings.creator_id')
            ->leftJoin('company_settings', function ($join) use ($keyMinutesRepeat) {
                $join->on('company_settings.company_id', '=', 'users.company_id')
                    ->where('company_settings.key', '=', $keyMinutesRepeat);
            })
            ->leftJoin('company_settings as cs1', function ($join) use ($keyIsSendNotify) {
                $join->on('cs1.company_id', '=', 'users.company_id')
                    ->where('cs1.key', '=', $keyIsSendNotify);
            })
            ->where(function ($query) use ($keyIsSendNotify) {
                $query->where('cs1.value', CompanySetting::$key[$keyIsSendNotify])
                    ->orWhereNull('cs1.value');
            })
            ->where('bookings.approved', config('settings.approved.tmp'))
            ->where('bookings.updated_at', '<=', \DB::raw('DATE_SUB(NOW(),INTERVAL IFNULL(company_settings.`value`,' . CompanySetting::$key[$keyMinutesRepeat] . ') MINUTE)'))
//		       ->where('updated_at', '<=', Carbon::now()->subMinutes(5))
            ->orderBy('updated_at', 'asc')
            ->groupBy('bookings.id')
            ->chunk(100, function ($bookings) {
                foreach ($bookings as $booking) {
                    //if not send notify - created_at <= now() - 5 minutes
                    $isNotify = Notification::select('created_at')->where('data->type', config('settings.notification_type.booking_tmp'))->where('data->booking->id', $booking->id)->orderBy('created_at', 'desc')->where('created_at', '>', Carbon::now()->subMinutes($booking->minutes_repeat))->count();
                    if (!$isNotify) {
                        event(new BookingEvent(config('settings.notification_type.booking_tmp'), $booking));
                    }
                }
            });
    }

    static function getCodeUnique($length = 10)
    {
        $codeAlphabet = "0123456789";
        $codeAlphabet .= "0123456789";
        $max = strlen($codeAlphabet); // edited

        do {
            $code = "#";
            for ($i = 0; $i < $length; $i++) {
                $code .= $codeAlphabet[random_int(0, $max - 1)];
            }
        } while (Booking::where('code', $code)->count() != 0);

        return $code;
    }

    const REPORT_TYPE = [
        'NUMBER' => 'number',
        'PEOPLE' => 'people',
        'FINANCE' => 'finance',
        'CREATED' => 'created_at',
        'UPDATED' => 'updated_at'
    ];

    /**
     * Report number booking group by Date
     * @param $report - self::REPORT_TYPE
     * @param $type - journeys|tours
     * @param $from - date Y-m-d
     * @param null $to - date Y-m-d
     * @param null $item_id - journeys|tours id
     * @param null $creator_id - (user) creator id
     * @param null $agent_id - agent id
     */
    static public function reportBookingByDate($report, $module_table = null, $from, $to = null, $service_id = null, $agent_id = null, $creator_id = null, $route_module)
    {
        if (empty($from)) return;

        //prepare
        if (!empty($from)) $from .= " 00:00:00";
        if (!empty($to)) $to .= " 23:59:59";

        $selectReport = "";
        switch ($report) {
            case self::REPORT_TYPE['NUMBER']:
                $selectReport = "COUNT(bookings.id) as number";
                break;
            case self::REPORT_TYPE['PEOPLE']:
                $selectReport = "SUM(adult_number+child_number) as number";
                break;
            case self::REPORT_TYPE['FINANCE']:
                $selectReport = "SUM(total_price) as number";
                break;
        }
        $select = \DB::raw('DATE(booking_property_values.value) as date,' . $selectReport);
        $groupBy = \DB::raw('DATE(booking_property_values.value)');
        //query
        $bookingData = Booking::byRole()->join('booking_property_values', 'booking_property_values.booking_id', '=', 'bookings.id')
            ->join('booking_properties', 'booking_property_values.booking_property_id', '=', 'booking_properties.id')
            ->select($select);
        $bookingData = $bookingData->where('key', 'departure_date');

        $bookingData = $bookingData->where('booking_property_values.value', '>=', $from);

        if (!empty($to)) $bookingData = $bookingData->where('booking_property_values.value', '<=', $to);

        if (!empty($service_id)) {
            $bookingData = $bookingData->whereHas('services', function ($query) use ($module_table, $service_id) {
                $query->withTrashed()->where($module_table . '.id', '=', $service_id);
            });
        }
        if (!empty($agent_id)) {
            $bookingData = $bookingData->whereHas('creator', function ($query) use ($agent_id) {
                $query->withTrashed()->where('agent_id', '=', $agent_id);
            });
        }
        if (!empty($creator_id)) {
            $bookingData = $bookingData->where('creator_id', $creator_id);
        }

        $bookingData = $bookingData->groupBy($groupBy)->orderBy($groupBy)->pluck('number', 'date');
        return $bookingData;
    }

    /**
     * Report number booking group by Month
     * @param $fromYear - year Y
     * @param null $creator_id - (user) creator id
     * @param null $agent_id - agent id
     */
    public static function reportBookingByMonth($report, $module_table = null, $fromYear, $service_id = null, $agent_id = null, $creator_id = null, $route_module)
    {
        if (empty($fromYear)) return;

        $selectReport = "";
        switch ($report) {
            case self::REPORT_TYPE['NUMBER']:
                $selectReport = "COUNT(bookings.id) as number";
                break;
            case self::REPORT_TYPE['PEOPLE']:
                $selectReport = "SUM(adult_number+child_number) as number";
                break;
            case self::REPORT_TYPE['FINANCE']:
                $selectReport = "SUM(total_price) as number";
                break;
        }

        $select = \DB::raw('MONTH(booking_property_values.value) as date,' . $selectReport);
        $groupBy = \DB::raw('MONTH(booking_property_values.value)');

        //query
        $bookingData = Booking::byRole()->join('booking_property_values', 'booking_property_values.booking_id', '=', 'bookings.id')
            ->join('booking_properties', 'booking_property_values.booking_property_id', '=', 'booking_properties.id')
            ->select($select);

        $bookingData = $bookingData->where('key', 'departure_date');

        $bookingData = $bookingData->where('booking_property_values.value', '>=', $fromYear . '-01-01 00:00:00')->where('booking_property_values.value', '<=', $fromYear . '-12-31 23:59:59');

        if (!empty($service_id)) {
            $bookingData = $bookingData->whereHas('services', function ($query) use ($module_table, $service_id) {
                $query->withTrashed()->where($module_table . '.id', '=', $service_id);
            });
        }
        if (!empty($agent_id)) {
            $bookingData = $bookingData->whereHas('creator', function ($query) use ($agent_id) {
                $query->withTrashed()->where('agent_id', '=', $agent_id);
            });
        }

        if (!empty($creator_id)) {
            $bookingData = $bookingData->where('creator_id', $creator_id);
        }

        $bookingData = $bookingData->groupBy($groupBy)->orderBy($groupBy)->pluck('number', 'date');
        return $bookingData;
    }

    /**
     * Report number booking group by Year
     * @param $fromYear - year Y
     * @param null $creator_id - (user) creator id
     * @param null $agent_id - agent id
     */
    public static function reportBookingByYear($report, $module_table = null, $fromYear, $service_id = null, $agent_id = null, $creator_id = null, $route_module)
    {
        if (empty($fromYear)) return;

        $selectReport = "";
        switch ($report) {
            case self::REPORT_TYPE['NUMBER']:
                $selectReport = "COUNT(bookings.id) as number";
                break;
            case self::REPORT_TYPE['PEOPLE']:
                $selectReport = "SUM(adult_number+child_number) as number";
                break;
            case self::REPORT_TYPE['FINANCE']:
                $selectReport = "SUM(total_price) as number";
                break;
        }

        $select = \DB::raw('YEAR(booking_property_values.value) as date,' . $selectReport);
        $groupBy = \DB::raw('YEAR(booking_property_values.value)');

        //query
        $bookingData = Booking::byRole()->join('booking_property_values', 'booking_property_values.booking_id', '=', 'bookings.id')
            ->join('booking_properties', 'booking_property_values.booking_property_id', '=', 'booking_properties.id')
            ->select($select);

        $bookingData = $bookingData->where('key', 'departure_date');

        $bookingData = $bookingData->where('booking_property_values.value', '>=', '2018-01-01 00:00:00');//bắt đầu mặc định từ năm 2018
        $bookingData = $bookingData->where('booking_property_values.value', '<=', $fromYear . '-12-31 00:00:00');//tới năm được chọn

        if (!empty($service_id)) {
            $bookingData = $bookingData->whereHas('services', function ($query) use ($module_table, $service_id) {
                $query->withTrashed()->where($module_table . '.id', '=', $service_id);
            });
        }
        if (!empty($agent_id)) {
            $bookingData = $bookingData->whereHas('creator', function ($query) use ($agent_id) {
                $query->withTrashed()->where('agent_id', '=', $agent_id);
            });
        }

        if (!empty($creator_id)) {
            $bookingData = $bookingData->where('creator_id', $creator_id);
        }

        $bookingData = $bookingData->groupBy($groupBy)->orderBy($groupBy)->pluck('number', 'date');
        return $bookingData;
    }

    /**
     * Where by role
     * @param $query
     * @param $type : ap dung cho booking: tours|journeys
     *
     * @return mixed
     */
    public function scopeByRole($query)
    {
        if (\Auth::check()) {
            //book dịch vụ
            //Tìm hiểu thêm services là gì, tại sao lại dùng services để query
            $query = $query->whereHas('services', function ($query) {
                $query->withTrashed();
            });
//			return $query;d

            //Booking
            if (\Auth::user()->roleBelongToAgent()) {
                if (\Auth::user()->isEmployeeAgent()) {//nhân viên đại lý: hiển thị booking của chính mình tạo
                    return $query->where('creator_id', \Auth::user()->id);
                } else {//admin đại lý: hiển thị tất cả các booking của đại lý này
                    return $query->whereHas('creator', function ($query) {
                        $query->withTrashed()->where('agent_id', '=', \Auth::user()->agent_id);
                    });
                }
            } elseif (\Auth::user()->roleBelongToCustomer()) {
                return $query->where('creator_id', \Auth::user()->id);
            } else {
                if (\Auth::user()->isEmployeeCompany()) {//Nhân viên công ty: hiển thị booking của chính họ tạo và booking họ quản lý
                    return $query->where(function ($query) {
                        $query->where('creator_id', \Auth::user()->id)
                            ->orWhereHas('services', function ($query) {
                                $query->whereHas('managers', function ($query) {
                                    $query->where('user_id', \Auth::user()->id);
                                });
                            });
                    });
                }
                return $query;
            }
        }
    }
}

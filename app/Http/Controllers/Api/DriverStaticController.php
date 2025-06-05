<?php

namespace App\Http\Controllers\Api;


use App\Models\WalletTransaction;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class DriverStaticController extends BaseController
{


    ## 📊 Lấy thông tin tổng quan thống kê cho driver
    /**
     * @OA\Get(
     *     path="/api/v1/driver/statistics/overview",
     *     tags={"Driver Static"},
     *     summary="Get static over",
     *     @OA\Parameter(
     *         name="period",
     *         in="query",
     *         description="period (thisWeek)",
     *         required=false,
     *         @OA\Schema(type="string", default="thisWeek")
     *     ),
     *     @OA\Response(response="200", description="Get static over"),
     *     security={{"bearerAuth":{}}},
     * )
     */
    public function getStaticOverView(Request $request)
    {

        try {
            $driverId = auth('api')->id(); // Giả sử driver đã đăng nhập
            $period = $request->input('period', 'thisWeek');
            $startDate = null;
            $endDate = null;

            // Xử lý mốc thời gian theo period
            switch ($period) {
                case 'today':
                    $startDate = Carbon::today();
                    $endDate = Carbon::now();
                    break;
                case 'yesterday':
                    $startDate = Carbon::yesterday();
                    $endDate = Carbon::yesterday()->endOfDay();
                    break;
                case 'thisWeek':
                    $startDate = Carbon::now()->startOfWeek();
                    $endDate = Carbon::now()->endOfWeek();
                    break;
                case 'lastWeek':
                    $startDate = Carbon::now()->subWeek()->startOfWeek();
                    $endDate = Carbon::now()->subWeek()->endOfWeek();
                    break;
                case 'thisMonth':
                    $startDate = Carbon::now()->startOfMonth();
                    $endDate = Carbon::now()->endOfMonth();
                    break;
                case 'lastMonth':
                    $startDate = Carbon::now()->subMonth()->startOfMonth();
                    $endDate = Carbon::now()->subMonth()->endOfMonth();
                    break;
                case 'thisYear':
                    $startDate = Carbon::now()->startOfYear();
                    $endDate = Carbon::now()->endOfYear();
                    break;
                case 'custom':
                    $startDate = Carbon::parse($request->input('startDate'));
                    $endDate = Carbon::parse($request->input('endDate'))->endOfDay();
                    break;
                default:
                    return $this->sendError('Invalid period');
            }
            // Tổng doanh thu (gross income)
            $grossIncome = WalletTransaction::where('user_id', $driverId)
                ->where('type', 'purchase')
                ->where('status', 'completed')
                ->whereBetween('transaction_date', [$startDate, $endDate])
                ->sum('price');

            // Thu nhập thực nhận (net income): giả sử đã trừ phí, lấy `base_price`
            $netIncome = WalletTransaction::where('user_id', $driverId)
                ->where('type', 'purchase')
                ->where('status', 'completed')
                ->whereBetween('transaction_date', [$startDate, $endDate])
                ->sum('base_price');

            // Số chuyến
            $totalTrips = WalletTransaction::where('user_id', $driverId)
                ->where('type', 'in')
                ->whereNotNull('order_id')
                ->whereBetween('transaction_date', [$startDate, $endDate])
                ->count();

            // Đánh giá trung bình (giả định bạn có bảng ratings)
            $avgRating = \App\Models\CustomerRating::where('user_id', $driverId)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->avg('star');

            $totalRatings = \App\Models\CustomerRating::where('user_id', $driverId)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count();

            // Tổng giờ online (nếu có bảng log giờ online → chỉ ví dụ)
            $totalOnlineMinutes = 510; // Giả định có tính toán được

            return $this->sendResponse([
                'netIncome' => [
                    'amount' => round($netIncome, 2),
                    'currency' => 'HUF',
                    'changePercentage' => 12.5, // Giả định - cần tính so với kỳ trước nếu cần
                    'changeDirection' => 'up'
                ],
                'grossIncome' => [
                    'amount' => round($grossIncome, 2),
                    'currency' => 'HUF',
                ],
                'stats' => [
                    'totalTrips' => [
                        'count' => $totalTrips,
                        'changePercentage' => 8.3,
                        'changeDirection' => 'up'
                    ],
                    'onlineHours' => [
                        'hours' => floor($totalOnlineMinutes / 60),
                        'minutes' => $totalOnlineMinutes % 60,
                        'totalMinutes' => $totalOnlineMinutes,
                        'changePercentage' => -2.1,
                        'changeDirection' => 'down'
                    ],
                    'averageRating' => [
                        'rating' => round($avgRating ?? 5, 1),
                        'totalRatings' => $totalRatings,
                        'changePercentage' => 0.5,
                        'changeDirection' => 'up'
                    ]
                ],
                'period' => [
                    'type' => $period,
                    'startDate' => $startDate->toDateString(),
                    'endDate' => $endDate->toDateString(),
                    'displayName' => ucfirst(str_replace(['this', 'last'], ['This ', 'Last '], $period))
                ]
            ], __('GET_STATIC_SUCCESS'));
        } catch (\Exception $e) {
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
        }
    }


    ## 2 Lấy dữ liệu cho biểu đồ thu nhập (Bar Chart)
    /**
     * @OA\Get(
     *     path="/api/v1/driver/statistics/income-chart",
     *     tags={"Driver Static"},
     *     summary="Get income-chart",
     *     @OA\Parameter(
     *         name="period",
     *         in="query",
     *         description="period (thisWeek)",
     *         required=false,
     *         @OA\Schema(type="string", default="thisWeek")
     *     ),
     *     @OA\Parameter(
     *         name="start_date",
     *         in="query",
     *         description="Start date (2025-01-05)",
     *         required=false,
     *         @OA\Schema(type="date")
     *     ),
     *     @OA\Parameter(
     *         name="end_date",
     *         in="query",
     *         description="End date (2025-01-05)",
     *         required=false,
     *         @OA\Schema(type="date")
     *     ),
     *     @OA\Parameter(
     *         name="group_by",
     *         in="query",
     *         description="group_by (day|week|month)",
     *         required=false,
     *         @OA\Schema(type="string", default="day")
     *     ),
     *     @OA\Response(response="200", description="Get income-chart"),
     *     security={{"bearerAuth":{}}},
     * )
     */
    public function getStaticIncomeChart(Request $request)
    {

        try {
            $driverId = auth('api')->id(); // Giả sử driver đã đăng nhập
            $period = $request->input('period', 'thisWeek');
            $groupBy = $request->input('group_by', 'day');

            // Xác định khoảng thời gian
            switch ($period) {
                case 'today':
                    $start = Carbon::today();
                    $end = Carbon::now();
                    break;
                case 'yesterday':
                    $start = Carbon::yesterday();
                    $end = Carbon::yesterday()->endOfDay();
                    break;
                case 'thisWeek':
                    $start = Carbon::now()->startOfWeek();
                    $end = Carbon::now()->endOfWeek();
                    break;
                case 'lastWeek':
                    $start = Carbon::now()->subWeek()->startOfWeek();
                    $end = Carbon::now()->subWeek()->endOfWeek();
                    break;
                case 'thisMonth':
                    $start = Carbon::now()->startOfMonth();
                    $end = Carbon::now()->endOfMonth();
                    break;
                case 'lastMonth':
                    $start = Carbon::now()->subMonth()->startOfMonth();
                    $end = Carbon::now()->subMonth()->endOfMonth();
                    break;
                case 'custom':
                    $start = Carbon::parse($request->input('start_date'));
                    $end = Carbon::parse($request->input('end_date'))->endOfDay();
                    break;
                default:
                    return response()->json(['status' => false, 'message' => 'Invalid period'], 400);
            }

            // Lấy dữ liệu giao dịch
            $transactions = WalletTransaction::where('user_id', $driverId)
                ->where('type', 'purchase')
                ->where('status', 'completed')
                ->whereBetween('transaction_date', [$start, $end])
                ->get();

            $chartData = [];
            $labels = [];
            $total = 0;

            if ($groupBy === 'day') {
                $range = CarbonPeriod::create($start, $end);
                foreach ($range as $day) {
                    $label = $day->format('D'); // Mon, Tue,...
                    $date = $day->format('Y-m-d');
                    $value = $transactions
                        ->filter(fn($t) => Carbon::parse($t->transaction_date)->toDateString() === $date)
                        ->sum('price');

                    $chartData[] = [
                        'label' => $label,
                        'date' => $date,
                        'value' => round($value, 2),
                        'currency' => 'HUF'
                    ];
                    $total += $value;
                }
            }

            if ($groupBy === 'week') {
                $current = $start->copy()->startOfWeek();
                $i = 1;
                while ($current <= $end) {
                    $weekStart = $current->copy();
                    $weekEnd = $current->copy()->endOfWeek();

                    $value = $transactions
                        ->filter(fn($t) => Carbon::parse($t->transaction_date)->between($weekStart, $weekEnd))
                        ->sum('price');

                    $chartData[] = [
                        'label' => 'Week ' . $i++,
                        'date' => $weekStart->format('Y-m-d'),
                        'value' => round($value, 2),
                        'currency' => 'HUF'
                    ];
                    $total += $value;
                    $current->addWeek();
                }
            }

            if ($groupBy === 'month') {
                $current = $start->copy()->startOfMonth();
                while ($current <= $end) {
                    $monthStart = $current->copy()->startOfMonth();
                    $monthEnd = $current->copy()->endOfMonth();

                    $value = $transactions
                        ->filter(fn($t) => Carbon::parse($t->transaction_date)->between($monthStart, $monthEnd))
                        ->sum('price');

                    $chartData[] = [
                        'label' => $monthStart->format('M'),
                        'date' => $monthStart->format('Y-m-d'),
                        'value' => round($value, 2),
                        'currency' => 'HUF'
                    ];
                    $total += $value;
                    $current->addMonth();
                }
            }

            return $this->sendResponse([
                'chartData' => $chartData,
                'maxValue' => collect($chartData)->max('value'),
                'totalIncome' => round($total, 2),
                'averageIncome' => count($chartData) > 0 ? round($total / count($chartData), 2) : 0
            ], __('GET_STATIC_SUCCESS'));
        } catch (\Exception $e) {
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
        }
    }

    ## 3 Lấy dữ liệu cho biểu đồ chuyến đi (Line Chart)
    /**
     * @OA\Get(
     *     path="/api/v1/driver/statistics/trips-chart",
     *     tags={"Driver Static"},
     *     summary="Get trips-chart",
     *     @OA\Parameter(
     *         name="period",
     *         in="query",
     *         description="period (thisWeek)",
     *         required=false,
     *         @OA\Schema(type="string", default="thisWeek")
     *     ),
     *     @OA\Parameter(
     *         name="start_date",
     *         in="query",
     *         description="Start date (2025-01-05)",
     *         required=false,
     *         @OA\Schema(type="date")
     *     ),
     *     @OA\Parameter(
     *         name="end_date",
     *         in="query",
     *         description="End date (2025-01-05)",
     *         required=false,
     *         @OA\Schema(type="date")
     *     ),
     *     @OA\Parameter(
     *         name="group_by",
     *         in="query",
     *         description="group_by (day|week|month)",
     *         required=false,
     *         @OA\Schema(type="string", default="day")
     *     ),
     *     @OA\Response(response="200", description="Get trips-chart"),
     *     security={{"bearerAuth":{}}},
     * )
     */
    public function getStaticStripsChart(Request $request)
    {

        try {
            $driverId = auth('api')->id(); // Giả sử driver đã đăng nhập
            $period = $request->input('period', 'thisWeek');
            $groupBy = $request->input('group_by', 'day');
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');

            // Xác định khoảng thời gian
            switch ($period) {
                case 'custom':
                    $start = Carbon::parse($startDate);
                    $end = Carbon::parse($endDate)->endOfDay();
                    break;
                case 'today':
                    $start = Carbon::today();
                    $end = Carbon::now();
                    break;
                case 'yesterday':
                    $start = Carbon::yesterday();
                    $end = Carbon::yesterday()->endOfDay();
                    break;
                case 'thisWeek':
                    $start = Carbon::now()->startOfWeek();
                    $end = Carbon::now()->endOfWeek();
                    break;
                case 'lastWeek':
                    $start = Carbon::now()->subWeek()->startOfWeek();
                    $end = Carbon::now()->subWeek()->endOfWeek();
                    break;
                case 'thisMonth':
                    $start = Carbon::now()->startOfMonth();
                    $end = Carbon::now()->endOfMonth();
                    break;
                case 'lastMonth':
                    $start = Carbon::now()->subMonth()->startOfMonth();
                    $end = Carbon::now()->subMonth()->endOfMonth();
                    break;
                default:
                    return response()->json(['status' => false, 'message' => 'Invalid period'], 400);
            }

            // Truy vấn các giao dịch có order
            $transactions = WalletTransaction::where('user_id', $driverId)
                ->where('type', 'purchase')
                ->whereNotNull('order_id')
                ->whereBetween('transaction_date', [$start, $end])
                ->get();

            $chartData = [];
            $totalTrips = 0;
            $completedTripsTotal = 0;

            if ($groupBy === 'day') {
                $range = CarbonPeriod::create($start, $end);
                foreach ($range as $day) {
                    $date = $day->format('Y-m-d');
                    $label = $day->format('D');

                    $daily = $transactions->filter(fn($t) => Carbon::parse($t->transaction_date)->toDateString() === $date);
                    $completed = $daily->where('status', 'completed')->count();
                    $cancelled = $daily->where('status', 'cancelled')->count();
                    $total = $daily->count();

                    $chartData[] = [
                        'label' => $label,
                        'date' => $date,
                        'value' => $total,
                        'completedTrips' => $completed,
                        'cancelledTrips' => $cancelled,
                    ];

                    $totalTrips += $total;
                    $completedTripsTotal += $completed;
                }
            } elseif ($groupBy === 'week') {
                $current = $start->copy()->startOfWeek();
                $i = 1;
                while ($current <= $end) {
                    $weekStart = $current->copy();
                    $weekEnd = $current->copy()->endOfWeek();

                    $weekly = $transactions->filter(fn($t) =>
                    Carbon::parse($t->transaction_date)->between($weekStart, $weekEnd)
                    );

                    $completed = $weekly->where('status', 'completed')->count();
                    $cancelled = $weekly->where('status', 'cancelled')->count();
                    $total = $weekly->count();

                    $chartData[] = [
                        'label' => 'Week ' . $i++,
                        'date' => $weekStart->format('Y-m-d'),
                        'value' => $total,
                        'completedTrips' => $completed,
                        'cancelledTrips' => $cancelled,
                    ];

                    $totalTrips += $total;
                    $completedTripsTotal += $completed;
                    $current->addWeek();
                }
            } elseif ($groupBy === 'month') {
                $current = $start->copy()->startOfMonth();
                while ($current <= $end) {
                    $monthStart = $current->copy()->startOfMonth();
                    $monthEnd = $current->copy()->endOfMonth();

                    $monthly = $transactions->filter(fn($t) =>
                    Carbon::parse($t->transaction_date)->between($monthStart, $monthEnd)
                    );

                    $completed = $monthly->where('status', 'completed')->count();
                    $cancelled = $monthly->where('status', 'cancelled')->count();
                    $total = $monthly->count();

                    $chartData[] = [
                        'label' => $monthStart->format('M'),
                        'date' => $monthStart->format('Y-m-d'),
                        'value' => $total,
                        'completedTrips' => $completed,
                        'cancelledTrips' => $cancelled,
                    ];

                    $totalTrips += $total;
                    $completedTripsTotal += $completed;
                    $current->addMonth();
                }
            }


            return $this->sendResponse([
                'chartData' => $chartData,
                'maxValue' => collect($chartData)->max('value'),
                'totalTrips' => $totalTrips,
                'averageTrips' => count($chartData) > 0 ? round($totalTrips / count($chartData), 2) : 0,
                'completionRate' => $totalTrips > 0 ? round(($completedTripsTotal / $totalTrips) * 100, 2) : 0,
            ], __('GET_STATIC_SUCCESS'));
        } catch (\Exception $e) {
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
        }
    }

    ## 4 Lấy dữ liệu phân tích thu nhập chi tiết (Pie Chart)
    /**
     * @OA\Get(
     *     path="/api/v1/driver/statistics/income-breakdown",
     *     tags={"Driver Static"},
     *     summary="Get income-breakdown",
     *     @OA\Parameter(
     *         name="period",
     *         in="query",
     *         description="period (thisWeek)",
     *         required=false,
     *         @OA\Schema(type="string", default="thisWeek")
     *     ),
     *     @OA\Parameter(
     *         name="start_date",
     *         in="query",
     *         description="Start date (2025-01-05)",
     *         required=false,
     *         @OA\Schema(type="date")
     *     ),
     *     @OA\Parameter(
     *         name="end_date",
     *         in="query",
     *         description="End date (2025-01-05)",
     *         required=false,
     *         @OA\Schema(type="date")
     *     ),
     *     @OA\Parameter(
     *         name="group_by",
     *         in="query",
     *         description="group_by (day|week|month)",
     *         required=false,
     *         @OA\Schema(type="string", default="day")
     *     ),
     *     @OA\Response(response="200", description="Get income-breakdown"),
     *     security={{"bearerAuth":{}}},
     * )
     */
    public function getStaticIncomeBreakDown(Request $request)
    {

        try {
            $driverId = auth('api')->id(); // Giả sử driver đã đăng nhập
            $period = $request->input('period', 'thisWeek');
            $groupBy = $request->input('group_by', 'day');
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');

            // Xác định thời gian
            switch ($period) {
                case 'custom':
                    $start = Carbon::parse($startDate);
                    $end = Carbon::parse($endDate)->endOfDay();
                    break;
                case 'today':
                    $start = Carbon::today();
                    $end = Carbon::now();
                    break;
                case 'thisWeek':
                    $start = Carbon::now()->startOfWeek();
                    $end = Carbon::now()->endOfWeek();
                    break;
                case 'thisMonth':
                    $start = Carbon::now()->startOfMonth();
                    $end = Carbon::now()->endOfMonth();
                    break;
                default:
                    return response()->json(['status' => false, 'message' => 'Invalid period'], 400);
            }

            // Dữ liệu thu nhập
            $grossIncome = WalletTransaction::where('user_id', $driverId)
                ->where('type', 'purchase')
                ->where('status', 'completed')
                ->whereBetween('transaction_date', [$start, $end])
                ->sum('price');

            $netIncome = WalletTransaction::where('user_id', $driverId)
                ->where('type', 'purchase')
                ->where('status', 'completed')
                ->whereBetween('transaction_date', [$start, $end])
                ->sum('base_price');

            // Các giả định khấu trừ (có thể lấy từ DB thật nếu có)
            $platformFee = round($grossIncome * 0.15, 2);
            $fuelMaintenance = 35.00;
            $insurance = 12.24;

            // Tổng khấu trừ thực tế = Gross - Net (nếu bạn không có log chi tiết)
            $totalDeductions = $grossIncome - $netIncome;

            // Tính tỷ lệ phần trăm an toàn
            $breakdown = [
                [
                    "type" => "netIncome",
                    "label" => "Net Income",
                    "amount" => round($netIncome, 2),
                    "percentage" => $grossIncome > 0 ? round(($netIncome / $grossIncome) * 100, 1) : 0,
                    "color" => "#4CAF50",
                    "isPositive" => true,
                ],
                [
                    "type" => "platformFee",
                    "label" => "Platform Fee (15%)",
                    "amount" => $platformFee,
                    "percentage" => $grossIncome > 0 ? round(($platformFee / $grossIncome) * 100, 1) : 0,
                    "color" => "#F44336",
                    "isPositive" => false,
                ],
                [
                    "type" => "fuelMaintenance",
                    "label" => "Fuel & Maintenance",
                    "amount" => $fuelMaintenance,
                    "percentage" => $grossIncome > 0 ? round(($fuelMaintenance / $grossIncome) * 100, 1) : 0,
                    "color" => "#FF9800",
                    "isPositive" => false,
                ],
                [
                    "type" => "insurance",
                    "label" => "Insurance",
                    "amount" => $insurance,
                    "percentage" => $grossIncome > 0 ? round(($insurance / $grossIncome) * 100, 1) : 0,
                    "color" => "#2196F3",
                    "isPositive" => false,
                ],
            ];

            return $this->sendResponse([
                "grossIncome" => round($grossIncome, 2),
                "breakdown" => $breakdown,
                "netIncomePercentage" => $grossIncome > 0 ? round(($netIncome / $grossIncome) * 100, 1) : 0,
                "totalDeductions" => $grossIncome > 0 ? round($grossIncome - $netIncome, 2) : 0
            ], __('GET_STATIC_SUCCESS'));
        } catch (\Exception $e) {
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
        }
    }

    ## 5 Lấy dữ liệu cho biểu đồ thời gian online (Bar Chart)
    /**
     * @OA\Get(
     *     path="/api/v1/driver/statistics/time-chart",
     *     tags={"Driver Static"},
     *     summary="Get time-chart",
     *     @OA\Parameter(
     *         name="period",
     *         in="query",
     *         description="period (thisWeek)",
     *         required=false,
     *         @OA\Schema(type="string", default="thisWeek")
     *     ),
     *     @OA\Parameter(
     *         name="start_date",
     *         in="query",
     *         description="Start date (2025-01-05)",
     *         required=false,
     *         @OA\Schema(type="date")
     *     ),
     *     @OA\Parameter(
     *         name="end_date",
     *         in="query",
     *         description="End date (2025-01-05)",
     *         required=false,
     *         @OA\Schema(type="date")
     *     ),
     *     @OA\Parameter(
     *         name="group_by",
     *         in="query",
     *         description="group_by (day|week|month)",
     *         required=false,
     *         @OA\Schema(type="string", default="day")
     *     ),
     *     @OA\Response(response="200", description="Get time-chart"),
     *     security={{"bearerAuth":{}}},
     * )
     */
    public function getStaticTimeChart(Request $request)
    {

        try {
            $driverId = auth('api')->id(); // Giả sử driver đã đăng nhập
            $period = $request->input('period', 'thisWeek');
            $groupBy = $request->input('group_by', 'day');
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');


            // Xác định khoảng thời gian
            switch ($period) {
                case 'custom':
                    $start = Carbon::parse($startDate);
                    $end = Carbon::parse($endDate)->endOfDay();
                    break;
                case 'today':
                    $start = Carbon::today();
                    $end = Carbon::now();
                    break;
                case 'thisWeek':
                    $start = Carbon::now()->startOfWeek();
                    $end = Carbon::now()->endOfWeek();
                    break;
                case 'thisMonth':
                    $start = Carbon::now()->startOfMonth();
                    $end = Carbon::now()->endOfMonth();
                    break;
                default:
                    return response()->json(['success' => false, 'message' => 'Invalid period'], 400);
            }

            // Giả sử có bảng driver_time_logs (chứa online & active minutes mỗi ngày)
            $logs = \DB::table('driver_time_logs')
                ->where('driver_id', $driverId)
                ->whereBetween('date', [$start->toDateString(), $end->toDateString()])
                ->get()
                ->keyBy('date');

            $chartData = [];
            $totalOnlineMinutes = 0;
            $totalActiveMinutes = 0;

            foreach (CarbonPeriod::create($start, $end) as $day) {
                $date = $day->format('Y-m-d');
                $label = $day->format('D');

                $log = $logs->get($date);
                $online = $log->online_minutes ?? 0;
                $active = $log->active_minutes ?? 0;
                $idle = max(0, $online - $active);

                $chartData[] = [
                    'label' => $label,
                    'date' => $date,
                    'value' => round($online / 60, 1), // giờ
                    'onlineMinutes' => $online,
                    'activeMinutes' => $active,
                    'idleMinutes' => $idle,
                ];

                $totalOnlineMinutes += $online;
                $totalActiveMinutes += $active;
            }

            $daysCount = count($chartData);
            $efficiency = $totalOnlineMinutes > 0 ? round(($totalActiveMinutes / $totalOnlineMinutes) * 100, 1) : 0;


            return $this->sendResponse([
                'chartData' => $chartData,
                'maxValue' => 10.0,
                'totalOnlineHours' => round($totalOnlineMinutes / 60, 1),
                'averageOnlineHours' => $daysCount > 0 ? round($totalOnlineMinutes / 60 / $daysCount, 2) : 0,
                'efficiency' => $efficiency
            ], __('GET_STATIC_SUCCESS'));
        } catch (\Exception $e) {
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
        }
    }


    ## 6 Lấy dữ liệu chi tiết cho Income Breakdown List
    /**
     * @OA\Get(
     *     path="/api/v1/driver/statistics/details",
     *     tags={"Driver Static"},
     *     summary="Get all support chanels",
     *     @OA\Parameter(
     *         name="period",
     *         in="query",
     *         description="period (thisWeek)",
     *         required=false,
     *         @OA\Schema(type="string", default="thisWeek")
     *     ),
     *     @OA\Parameter(
     *         name="start_date",
     *         in="query",
     *         description="Start date (2025-01-05)",
     *         required=false,
     *         @OA\Schema(type="date")
     *     ),
     *     @OA\Parameter(
     *         name="end_date",
     *         in="query",
     *         description="End date (2025-01-05)",
     *         required=false,
     *         @OA\Schema(type="date")
     *     ),
     *     @OA\Parameter(
     *         name="group_by",
     *         in="query",
     *         description="group_by (day|week|month)",
     *         required=false,
     *         @OA\Schema(type="string", default="day")
     *     ),
     *     @OA\Response(response="200", description="Get support chanels"),
     *     security={{"bearerAuth":{}}},
     * )
     */
    public function getStaticDetail(Request $request)
    {

        try {
            $driverId = auth('api')->id(); // Giả sử driver đã đăng nhập
            $period = $request->input('period', 'thisWeek');
            $groupBy = $request->input('group_by', 'day');
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');

            // Xác định khoảng thời gian
            switch ($period) {
                case 'custom':
                    $start = Carbon::parse($startDate);
                    $end = Carbon::parse($endDate)->endOfDay();
                    break;
                case 'today':
                    $start = Carbon::today();
                    $end = Carbon::now();
                    break;
                case 'thisWeek':
                    $start = Carbon::now()->startOfWeek();
                    $end = Carbon::now()->endOfWeek();
                    break;
                case 'thisMonth':
                    $start = Carbon::now()->startOfMonth();
                    $end = Carbon::now()->endOfMonth();
                    break;
                default:
                    return response()->json(['status' => false, 'message' => 'Invalid period'], 400);
            }


            // Truy vấn tổng thu nhập và các khoản liên quan
            $grossIncome = WalletTransaction::where('user_id', $driverId)
                ->where('type', 'purchase')
                ->where('status', 'completed')
                ->whereBetween('transaction_date', [$start, $end])
                ->sum('price');

            $netIncome = WalletTransaction::where('user_id', $driverId)
                ->where('type', 'purchase')
                ->where('status', 'completed')
                ->whereBetween('transaction_date', [$start, $end])
                ->sum('base_price');

            // Giả định các chi phí (có thể lấy từ cấu hình hệ thống nếu cần)
            $platformFee = round($grossIncome * 0.15, 2);
            $fuelMaintenance = 35.00;
            $insurance = 12.24;

            $totalDeductions = $platformFee + $fuelMaintenance + $insurance;
            $profitMargin = $grossIncome > 0 ? round(($netIncome / $grossIncome) * 100, 1) : 0;

            $incomeDetails = [
                [
                    "type" => "grossIncome",
                    "label" => "Gross Income",
                    "amount" => round($grossIncome, 2),
                    "currency" => "HUF",
                    "isPositive" => true,
                    "isTotal" => false,
                    "description" => "Total earnings before deductions"
                ],
                [
                    "type" => "platformFee",
                    "label" => "Platform Fee (15%)",
                    "amount" => -$platformFee,
                    "currency" => "HUF",
                    "isPositive" => false,
                    "isTotal" => false,
                    "description" => "Commission fee to platform"
                ],
                [
                    "type" => "fuelMaintenance",
                    "label" => "Fuel & Maintenance",
                    "amount" => -$fuelMaintenance,
                    "currency" => "HUF",
                    "isPositive" => false,
                    "isTotal" => false,
                    "description" => "Vehicle operating costs"
                ],
                [
                    "type" => "insurance",
                    "label" => "Insurance",
                    "amount" => -$insurance,
                    "currency" => "HUF",
                    "isPositive" => false,
                    "isTotal" => false,
                    "description" => "Insurance coverage costs"
                ],
                [
                    "type" => "netIncome",
                    "label" => "Net Income",
                    "amount" => round($netIncome, 2),
                    "currency" => "HUF",
                    "isPositive" => true,
                    "isTotal" => true,
                    "description" => "Final earnings after all deductions"
                ]
            ];


            return $this->sendResponse( [
                "incomeDetails" => $incomeDetails,
                "summary" => [
                    "totalGross" => round($grossIncome, 2),
                    "totalDeductions" => round($totalDeductions, 2),
                    "totalNet" => round($netIncome, 2),
                    "profitMargin" => $profitMargin
                ]
            ], __('GET_STATIC_SUCCESS'));
        } catch (\Exception $e) {
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
        }
    }


}

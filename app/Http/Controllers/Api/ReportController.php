<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\StoreRating;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class ReportController extends BaseController
{

    ## 1. API Lấy Tổng Quan Thống Kê
    /**
     * @OA\Get(
     *     path="/api/v1/reports/overview",
     *     tags={"Report"},
     *     summary="Get report overview",
     *     @OA\Parameter(
     *         name="store_id",
     *         in="query",
     *         description="Store Id",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="date",
     *         in="query",
     *         description="Date (2025-01-05)",
     *         required=false,
     *         @OA\Schema(type="date")
     *     ),
     *     @OA\Parameter(
     *         name="timezone",
     *         in="query",
     *         description="Timezone (default: Asia/Ho_Chi_Minh)",
     *         required=false,
     *         @OA\Schema(type="string", default="Asia/Ho_Chi_Minh")
     *     ),
     *     @OA\Response(response="200", description="Get report overview"),
     *     security={{"bearerAuth":{}}},
     * )
     */
    public function getOverview(Request $request)
    {
        $requestData = $request->all();
        $validator = \Validator::make($requestData, [
            'store_id' => 'required|exists:stores,id',
            'date' => 'required|date_format:Y-m-d',
        ]);
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));

        try {
            $storeId = $request->input('store_id');
            $timezone = $request->input('timezone', 'Asia/Ho_Chi_Minh');
            $date = $request->input('date', Carbon::now($timezone)->toDateString());

            // Parse ngày hiện tại & hôm qua theo timezone
            $today = Carbon::parse($date, $timezone)->startOfDay();
            $yesterday = (clone $today)->subDay();

            // Định nghĩa ngày bắt đầu & kết thúc để query
            $todayStart = $today->copy()->startOfDay()->setTimezone('UTC');
            $todayEnd = $today->copy()->endOfDay()->setTimezone('UTC');
            $yesterdayStart = $yesterday->copy()->startOfDay()->setTimezone('UTC');
            $yesterdayEnd = $yesterday->copy()->endOfDay()->setTimezone('UTC');

            // Doanh thu hôm nay
            $todayRevenue = Order::where('store_id', $storeId)
                ->whereBetween('created_at', [$todayStart, $todayEnd])
                ->where('process_status', 'completed')
                ->sum('total_price');

            // Đơn hôm nay
            $todayOrders = Order::where('store_id', $storeId)
                ->whereBetween('created_at', [$todayStart, $todayEnd])
                ->count('id');

            // Doanh thu hôm qua
            $yesterdayRevenue = Order::where('store_id', $storeId)
                ->whereBetween('created_at', [$yesterdayStart, $yesterdayEnd])
                ->where('process_status', 'completed')
                ->sum('total_price');

            // Đơn hôm qua
            $yesterdayOrders = Order::where('store_id', $storeId)
                ->whereBetween('created_at', [$yesterdayStart, $yesterdayEnd])
                ->count();

            // Tổng số khách hàng đã từng đặt đơn ở cửa hàng này
            $totalCustomers = Order::where('store_id', $storeId)
                ->distinct('user_id')
                ->count('user_id');

            // Giá trị đơn hàng trung bình hôm nay
            $avgOrderValue = $todayOrders > 0 ? round($todayRevenue / $todayOrders) : 0;

            // Tính tăng trưởng so với hôm qua (%)
            $growthRate = $yesterdayRevenue > 0
                ? round((($todayRevenue - $yesterdayRevenue) / $yesterdayRevenue) * 100, 2)
                : ($todayRevenue > 0 ? 100 : 0);


            return $this->sendResponse([
                'today_revenue' => $todayRevenue,
                'yesterday_revenue' => $yesterdayRevenue,
                'today_orders' => $todayOrders,
                'yesterday_orders' => $yesterdayOrders,
                'total_customers' => $totalCustomers,
                'avg_order_value' => $avgOrderValue,
                'growth_rate' => $growthRate,
            ], __('GET_REPORT_SUCCESS'));
        } catch (\Exception $e) {
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
        }
    }

    ## 2. API Lấy Dữ Liệu Biểu Đồ Doanh Thu
    /**
     * @OA\Get(
     *     path="/api/v1/reports/revenue-chart",
     *     tags={"Report"},
     *     summary="Get revenue-chart",
     *     @OA\Parameter(
     *         name="store_id",
     *         in="query",
     *         description="Store Id",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="period",
     *         in="query",
     *         description="daily|weekly|monthly",
     *         required=true,
     *         @OA\Schema(type="string", default="daily")
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
     *     @OA\Response(response="200", description="Get revenue-chart"),
     *     security={{"bearerAuth":{}}},
     * )
     */
    public function getRevenueChart(Request $request)
    {
        $requestData = $request->all();
        $validator = \Validator::make($requestData, [
            'store_id' => 'required|exists:stores,id',
            'period' => 'required|in:daily,weekly,monthly',
            'start_date' => 'required|date_format:Y-m-d',
            'end_date' => 'required|date_format:Y-m-d',
        ]);
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));

        try {

            $storeId = $request->input('store_id');
            $periodType = $request->input('period', 'daily');
            $startDate = Carbon::parse($request->input('start_date'));
            $endDate = Carbon::parse($request->input('end_date'));

            $orders = Order::where('store_id', $storeId)
                ->where('process_status', 'completed')
                ->whereBetween('created_at', [$startDate->copy()->startOfDay(), $endDate->copy()->endOfDay()])
                ->get();

            $daily = $weekly = $monthly = [];

            if ($periodType === 'daily') {
                $days = CarbonPeriod::create($startDate, $endDate);
                foreach ($days as $day) {
                    $date = $day->format('Y-m-d');
                    $total = $orders->whereBetween('created_at', [
                        $day->copy()->startOfDay(),
                        $day->copy()->endOfDay()
                    ])->sum('total_price');
                    $daily[] = [
                        'period' => $day->format('D'),
                        'value' => $total,
                        'date' => $date,
                    ];
                }
            }

            if ($periodType === 'weekly') {
                $current = $startDate->copy()->startOfWeek();
                $i = 1;
                while ($current < $endDate) {
                    $weekStart = $current->copy();
                    $weekEnd = $current->copy()->endOfWeek();
                    $total = $orders->whereBetween('created_at', [
                        $weekStart,
                        $weekEnd
                    ])->sum('total_price');
                    $weekly[] = [
                        'period' => 'Week ' . $i++,
                        'value' => $total,
                        'date' => $weekStart->format('Y-m-d'),
                    ];
                    $current->addWeek();
                }
            }

            if ($periodType === 'monthly') {
                $current = $startDate->copy()->startOfMonth();
                while ($current < $endDate) {
                    $monthStart = $current->copy()->startOfMonth();
                    $monthEnd = $current->copy()->endOfMonth();
                    $total = $orders->whereBetween('created_at', [
                        $monthStart,
                        $monthEnd
                    ])->sum('total_price');
                    $monthly[] = [
                        'period' => $current->format('M'),
                        'value' => $total,
                        'date' => $monthStart->format('Y-m-d'),
                    ];
                    $current->addMonth();
                }
            }


            return $this->sendResponse([
                'daily_revenue' => $daily,
                'weekly_revenue' => $weekly,
                'monthly_revenue' => $monthly,
            ], __('GET_REPORT_SUCCESS'));
        } catch (\Exception $e) {
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
        }
    }

    ## 3. API Lấy Món Ăn Bán Chạy
    /**
     * @OA\Get(
     *     path="/api/v1/reports/top-selling-items",
     *     tags={"Report"},
     *     summary="Get report top-selling-items",
     *     @OA\Parameter(
     *         name="store_id",
     *         in="query",
     *         description="Store Id",
     *         required=true,
     *         @OA\Schema(type="integer")
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
     *         name="limit",
     *         in="query",
     *         description="Limit",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="200", description="Get report top-selling-items"),
     *     security={{"bearerAuth":{}}},
     * )
     */
    public function getTopSellingItem(Request $request)
    {
        $requestData = $request->all();
        $validator = \Validator::make($requestData, [
            'store_id' => 'required|exists:stores,id',
            'start_date' => 'required|date_format:Y-m-d',
            'end_date' => 'required|date_format:Y-m-d',
        ]);
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));

        try {
            $storeId = $request->input('store_id');
            $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
            $endDate = Carbon::parse($request->input('end_date'))->endOfDay();
            $limit = (int) $request->input('limit', 10);

            // Lấy các đơn hàng đã hoàn tất trong khoảng thời gian
            $orders = Order::where('store_id', $storeId)
                ->where('process_status', 'completed')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->pluck('id')
                ->toArray();

            // Truy vấn OrderItem theo order_id
            $topItems = OrderItem::select(
                'product_id',
                \DB::raw('SUM(quantity) as total_quantity'),
                \DB::raw('SUM(price * quantity) as total_revenue')
            )
                ->whereIn('order_id', $orders)
                ->groupBy('product_id')
                ->orderByDesc('total_quantity')
                ->limit($limit)
                ->get();

            // Gán thêm thông tin từ bảng Product (nếu cần)
            $result = [];
            foreach ($topItems as $index => $item) {
                $product = \App\Models\Product::find($item->product_id);
                if ($product) {
                    $result[] = [
                        'item_id' => $product->id,
                        'name' => $product->name,
                        'image' => $product->image_url ?? null,
                        'quantity' => (int) $item->total_quantity,
                        'revenue' => (int) $item->total_revenue,
                        'rank' => $index + 1,
                    ];
                }
            }

            return $this->sendResponse($result, __('GET_REPORT_SUCCESS'));
        } catch (\Exception $e) {
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
        }
    }

    ## 4. API Lấy Đánh Giá Khách Hàng Gần Đây
    /**
     * @OA\Get(
     *     path="/api/v1/reports/recent-reviews",
     *     tags={"Report"},
     *     summary="Get report recent-reviews",
     *     @OA\Parameter(
     *         name="store_id",
     *         in="query",
     *         description="Store Id",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="days",
     *         in="query",
     *         description="Days",
     *         required=false,
     *         @OA\Schema(type="integer", default="30")
     *     ),
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         description="Limit",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="200", description="Get report recent-reviews"),
     *     security={{"bearerAuth":{}}},
     * )
     */
    public function getRecentReviews(Request $request)
    {
        $requestData = $request->all();
        $validator = \Validator::make($requestData, [
            'store_id' => 'required|exists:stores,id',
            'days' => 'required|integer',
        ]);
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));
        try {
            $storeId = $request->input('store_id');
            $limit = (int) $request->input('limit', 10);
            $days = (int) $request->input('days', 30);

            $startDate = Carbon::now()->subDays($days);

            $reviews = StoreRating::with('user')
                ->where('store_id', $storeId)
                ->where('created_at', '>=', $startDate)
                ->orderByDesc('created_at')
                ->limit($limit)
                ->get();

            $data = $reviews->map(function ($review) {
                return [
                    'review_id' => $review->id,
                    'customer_name' => optional($review->user)->name,
                    'customer_avatar' => optional($review->user)->avatar ?? url('images/user.png'),
                    'rating' => (float) $review->star,
                    'comment' => $review->content,
                    'created_at' => $review->created_at->toIso8601String(),
                ];
            });

            return $this->sendResponse($data, __('GET_REPORT_SUCCESS'));
        } catch (\Exception $e) {
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
        }
    }

    ## 5. API Lấy Đơn Hàng Gần Đây
    /**
     * @OA\Get(
     *     path="/api/v1/reports/recent-orders",
     *     tags={"Report"},
     *     summary="Get report recent-orders",
     *     @OA\Parameter(
     *         name="store_id",
     *         in="query",
     *         description="Store Id",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="days",
     *         in="query",
     *         description="Days",
     *         required=false,
     *         @OA\Schema(type="integer", default="7")
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Status (all,completed,processing,pending)",
     *         required=false,
     *         @OA\Schema(type="string", default="all")
     *     ),
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         description="Limit",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="200", description="Get report recent-orders"),
     *     security={{"bearerAuth":{}}},
     * )
     */
    public function getRecentOrders(Request $request)
    {
        $requestData = $request->all();
        $validator = \Validator::make($requestData, [
            'store_id' => 'required|exists:stores,id',
            'status' => 'required',
            'days' => 'required|integer',
        ]);
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));
        try {
            $storeId = $request->input('store_id');
            $limit = (int) $request->input('limit', 15);
            $days = (int) $request->input('days', 7);
            $status = $request->input('status', 'all');

            $query = Order::with(['customer', 'orderItems'])
                ->where('store_id', $storeId)
                ->where('created_at', '>=', Carbon::now()->subDays($days))
                ->orderByDesc('created_at');

            if (in_array($status, ['completed', 'processing', 'pending'])) {
                $query->where('process_status', $status);
            }

            $orders = $query->limit($limit)->get();

            $data = $orders->map(function ($order) {
                return [
                    'order_id' => $order->id,
                    'order_code' => $order->code,
                    'customer_name' => optional($order->customer)->name,
                    'customer_phone' => optional($order->customer)->phone,
                    'status' => $order->process_status,
                    'amount' => (int) $order->total_price,
                    'created_at' => $order->created_at->toIso8601String(),
                    'items' => $order->orderItems->map(function ($item) {
                        return [
                            'name' => $item->product['name'] ?? 'N/A',
                            'description' => $item->product['description'] ?? '',
                            'price' => (int) $item->product['price'] ?? 0,
                            'quantity' => (int) $item->quantity,
                        ];
                    })->toArray()
                ];
            });
            return $this->sendResponse($data, __('GET_REPORT_SUCCESS'));
        } catch (\Exception $e) {
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
        }
    }

    ## 6. API Lấy Đơn Hàng Bị Hủy
    /**
     * @OA\Get(
     *     path="/api/v1/reports/cancelled-orders",
     *     tags={"Report"},
     *     summary="Get report cancelled-orders",
     *     @OA\Parameter(
     *         name="store_id",
     *         in="query",
     *         description="Store Id",
     *         required=true,
     *         @OA\Schema(type="integer")
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
     *         name="limit",
     *         in="query",
     *         description="Limit",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="200", description="Get report cancelled-orders"),
     *     security={{"bearerAuth":{}}},
     * )
     */
    public function getCancelledOrders(Request $request)
    {
        $requestData = $request->all();
        $validator = \Validator::make($requestData, [
            'store_id' => 'required|exists:stores,id',
            'start_date' => 'required|date_format:Y-m-d',
            'end_date' => 'required|date_format:Y-m-d'
        ]);
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));
        try {
            $storeId = $request->input('store_id');
            $limit = (int) $request->input('limit', 20);
            $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
            $endDate = Carbon::parse($request->input('end_date'))->endOfDay();

            $orders = Order::with(['customer', 'orderItems'])
                ->where('store_id', $storeId)
                ->where('process_status', 'cancelled') // Trạng thái bị huỷ
                ->whereBetween('updated_at', [$startDate, $endDate])
                ->orderByDesc('updated_at')
                ->limit($limit)
                ->get();

            $data = $orders->map(function ($order) {
                return [
                    'order_id' => $order->id,
                    'order_code' => $order->code,
                    'customer_name' => optional($order->customer)->name,
                    'amount' => (int) $order->total_price,
                    'cancelled_at' => $order->updated_at->toIso8601String(),
                    'cancel_reason' => $order->cancel_note ?? 'Unknown',
                    'cancelled_by' => optional($order->customer)->name ?? 'Customer', // Hoặc trường riêng nếu có
                    'items' => $order->orderItems->map(function ($item) {
                        return [
                            'name' => $item->product['name'] ?? 'N/A',
                            'description' => $item->product['description'] ?? '',
                            'price' => (int) $item->product['price'] ?? 0,
                            'quantity' => (int) $item->quantity,
                        ];
                    })->toArray()
                ];
            });
            return $this->sendResponse($data, __('GET_REPORT_SUCCESS'));
        } catch (\Exception $e) {
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
        }
    }

    ## 7. API Lấy Metrics Hiệu Suất Kinh Doanh
    /**
     * @OA\Get(
     *     path="/api/v1/reports/performance-metrics",
     *     tags={"Report"},
     *     summary="Get report performance-metrics",
     *     @OA\Parameter(
     *         name="store_id",
     *         in="query",
     *         description="Store Id",
     *         required=true,
     *         @OA\Schema(type="integer")
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
     *     @OA\Response(response="200", description="Get report performance-metrics"),
     *     security={{"bearerAuth":{}}},
     * )
     */
    public function getPerformanceMetrics(Request $request)
    {
        $requestData = $request->all();
        $validator = \Validator::make($requestData, [
            'store_id' => 'required|exists:stores,id',
            'start_date' => 'required|date_format:Y-m-d',
            'end_date' => 'required|date_format:Y-m-d'
        ]);
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));
        try {
            $storeId = $request->input('store_id');
            $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
            $endDate = Carbon::parse($request->input('end_date'))->endOfDay();

            // Tổng đơn trong khoảng thời gian
            $totalOrders = Order::where('store_id', $storeId)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count();

            $completedOrders = Order::where('store_id', $storeId)
                ->where('process_status', 'completed')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count();

            $cancelledOrders = Order::where('store_id', $storeId)
                ->where('process_status', 'cancelled')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count();

            // Trung bình thời gian xử lý (giả sử có trường `processing_time` tính bằng phút)
            $avgProcessingTime = Order::where('store_id', $storeId)
                ->where('process_status', 'completed')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->avg('ship_estimate_time');

            // Trung bình thời gian giao hàng (giả sử có trường `delivery_duration_minutes`)
            $avgDeliveryTime = Order::where('store_id', $storeId)
                ->where('process_status', 'completed')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->avg('ship_distance'); // Hoặc trường riêng nếu có thời gian thực tế

            // Đánh giá trung bình
            $avgRating = StoreRating::where('store_id', $storeId)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->avg('rating');

            $successRate = $totalOrders > 0 ? round(($completedOrders / $totalOrders) * 100, 1) : 0;
            $cancelRate = $totalOrders > 0 ? round(($cancelledOrders / $totalOrders) * 100, 1) : 0;

            return $this->sendResponse([
                'success_rate' => $successRate,
                'cancel_rate' => $cancelRate,
                'avg_processing_time' => round($avgProcessingTime ?? 0),
                'avg_delivery_time' => round($avgDeliveryTime ?? 0),
                'avg_rating' => round($avgRating ?? 0, 1),
                'total_orders' => $totalOrders,
                'completed_orders' => $completedOrders,
                'cancelled_orders' => $cancelledOrders,
            ], __('GET_REPORT_SUCCESS'));
        } catch (\Exception $e) {
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
        }
    }

}

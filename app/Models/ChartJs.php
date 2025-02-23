<?php

namespace App;


use Carbon\Carbon;
// use Modules\Booking\Entities\Booking;
use App\Booking;
class ChartJs
{
	const COLOR = [
		"red" => '#f44336',
		"green" => '#66bb6a',
		"blue" => '#55acee',
		"aqua" => '#26c6da',
		"navy" => "#001F3F",
		"teal" => "#39CCCC",
		"olive" => "#01FF70",
		"orange" => "#FF851B",
		"fuchsia" => "#F012BE",
		"purple" => "#605ca8",
		"maroon" => "#D81B60",
		"black" => "#111",
	];

	public static function init(){
		echo '<script type="text/javascript" src="'.asset('plugins/chartjs/chartjs.min.js').'"></script>';
	}
	public static function generateDateRange(Carbon $start_date, Carbon $end_date) {
		$dates = [];
		for($date = $start_date; $date->lte($end_date); $date->addDay()) {
			$dates[] = $date->format('Y-m-d');
		}
		return $dates;
	}
	public static function dashboardLineCharBooking30DaysAgo(){
//        $user = \Auth::user();
//        if($user->roleBelongToCustomer()) return;
		?>
		<div class="box box-success">
			<div class="box-header with-border">
				<i class="fa fa-line-chart"></i>
				<div class="box-title text-bold">Booking 30 ngày gần đây</div>
			</div>
			<div class="box-body">
				<?php self::lineChartBooking30DaysAgo() ?>
			</div>
		</div>
<?php
	}
	public static function lineChartBooking30DaysAgo(){
		//prepare
		$date30Ago = date('Y-m-d', strtotime('-29 days'));
		$today = date('Y-m-d');
		//chart data
		$dates = Utils::generateDateRange(Carbon::parse($date30Ago), Carbon::parse($today));
        //get data
        $dateBooking30DaysAgo['datasets'][0] = [
            'label' => __('booking::bookings.services'),
            'borderColor' => ChartJs::COLOR['green'],
            'backgroundColor' => ChartJs::COLOR['green'],
            'fill' => false,
            'data' => []
        ];

        //get data
		$bookBus30DaysAgo = Booking::reportBookingByDate(Booking::REPORT_TYPE['NUMBER'],'bus', $date30Ago, $today, null, null, null, 'bus');

		//prepare data chart
		$datesFormat = [];
		foreach ($dates as $date) {
			$datesFormat[] = Carbon::parse($date)->format(config('settings.format.date'));
			$dateBooking30DaysAgo['datasets'][0]['data'][] = ! empty( $bookBus30DaysAgo[ $date ] ) ? $bookBus30DaysAgo[ $date ] : 0;
		}
		$dateBooking30DaysAgo['labels'] = $datesFormat;

		self::lineChart('30daysAgo', $dateBooking30DaysAgo);
	}
	public static function lineChart($id, $data, $height = '50vh'){
		print('
			<div style="position: relative; height:'.$height.';">
				<canvas id="'.$id.'"></canvas>
			</div>
				<script type="text/javascript">
				var numberWithCommas = function(x) {
                  return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                };
		        var _30daysAgo = document.getElementById("'.$id.'");
		        var myLineChart = new Chart(_30daysAgo, {
		            type: "line",
		            data: '.json_encode($data).',
		            options: {
		                legend :{
                          display: false //Ẩn Labels
                        },
		                maintainAspectRatio: false,
		                responsive: true,
		//                title: {
		//                    display: true,
		//                    text: "Booking 30 ngày gần đây"
		//                },
		                tooltips: {
		                    mode: "index",
		                    intersect: false,
		                    callbacks: {
                                label: function(tooltipItem, data) {
                                    var label = data.datasets[tooltipItem.datasetIndex].label || \'\';
                
                                    if (label) {
                                        label += \': \';
                                    }
                                    label += numberWithCommas(tooltipItem.yLabel);
                                    return label;
                                }
                            }
		                },
		                hover: {
		                    mode: "nearest",
		                    intersect: true
		                },
		                scales: {
                            yAxes: [{
                                ticks: {
                                    // Include a dollar sign in the ticks
                                    callback: function(value, index, values) {
                                        return numberWithCommas(value);
                                    }
                                }
                            }]
                        }
		//                ,
		//                scales: {
		//                    xAxes: [{
		//                        display: true,
		//                        scaleLabel: {
		//                            display: true,
		//                            labelString: "Ngày"
		//                        }
		//                    }],
		//                    yAxes: [{
		//                        display: true,
		//                        scaleLabel: {
		//                            display: true,
		//                            labelString: "Đặt vé"
		//                        }
		//                    }]
		//                }
		            }
		        });
		
			</script>
		');
	}
}

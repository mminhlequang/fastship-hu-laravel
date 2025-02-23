var ctx = document.getElementById('lineChart').getContext('2d');
var myChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 'Tháng 7', 'Tháng 8',
            'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'
        ],
        datasets: [{
            
                label: 'Số tiền (VNĐ)',

                data: [2800000, 1900000, 2100000, 2800000, 1800000, 2000000, 2500000, 2600000, 2450000, 1950000, 2300000, 2900000],
                backgroundColor: [
                    '#003B31'
                ],
                borderColor: 'rgb(41, 155, 99)',

                borderWidth: 1
            },
            {
                label: 'Số đơn',
                data: [28, 19, 21, 28, 18, 20, 25, 26, 24, 19, 23, 29],
                backgroundColor: [
                    '#ffa800'

                ],
                borderColor: '#ffcd00c7',

                borderWidth: 1
            }
        ]
    },
    options: {
        responsive: true
    }
});
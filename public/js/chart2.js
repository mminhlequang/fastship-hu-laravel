var ctx = document.getElementById('doughnut').getContext('2d');
var myChart = new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: ['Đơn thành công', 'Đơn huỷ', 'Khác'],

        datasets: [{
            label: 'Đơn hàng',
            data: [42, 12, 6],
            backgroundColor: [
                '#003B31',
                '#C56240',
                // 'rgba(255, 206, 86, 1)',
                '#ffa800'

            ],
            borderColor: [
                '#003B31',
                '#C56240',
                // 'rgba(255, 206, 86, 1)',
                '#ffa800'

            ],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true
    }
});
<script>
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ['Year', 'Sales', 'Refused'],
            <?php foreach($this->orders as $order): ?>
            <?php $date = isset($order[SOLD_OUT]) ? $order[SOLD_OUT][0] : $order[REFUSED][0];?>
            ['<?=$date?>',<?=isset($order[SOLD_OUT]) ? $order[SOLD_OUT][1] : 0?>,<?=isset($order[REFUSED]) ? $order[REFUSED][1] : 0?>],
            <?php endforeach; ?>
        ]);

        
        var options = {
            title: 'أداء الشركه',
            curveType: 'function',
            legend: {position : 'right'},
            series: {
                0: { color: '#c700ff' },
                1: { color: '#00d5ff' },
            },
            hAxis: { minValue: 0, maxValue: 9 },
            pointSize: 10,
            backgroundColor:'#222222',
            vAxis : {
                gridlines : {
                    color : '#424242'
                }
            }
        };

        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

        chart.draw(data, options);

    }
</script>
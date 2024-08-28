<div>
    <div style="width: 80%; margin: auto;">
        <canvas id="lineChart"></canvas>
    </div>
</div>

<script>
    var lineChart = document.getElementById('lineChart').getContext('2d');
    var lineChart = new Chart(lineChart, {
        type: 'line',
        data: {
            labels: @json($data['labels']),
            datasets: [{
                label: 'Data',
                data: @json($data['data']),
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

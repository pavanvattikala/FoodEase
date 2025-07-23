<div>
    <div style="width: 80%; margin: auto;">
        <canvas id="pieChart"></canvas>
    </div>
</div>

<script>
    const config = {
        type: 'pie',
        data: @json($data['data']),
        options: {
            plugins: {
                title: {
                    display: true,
                    text: 'Chart.js Stacked Line/Bar Chart'
                }
            },
            scales: {
                y: {
                    stacked: true
                }
            }
        },
    };
    var pieChart = document.getElementById('pieChart').getContext('2d');
    var pieChart = new Chart(pieChart, config);
</script>

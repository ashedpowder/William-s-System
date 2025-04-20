const ctx = document.getElementById('netProfitChart').getContext('2d');
const netProfitChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
        datasets: [{
            label: 'Net Profit',
            data: [50000, 60000, 70000, 80000, 75000, 85000, 90000],
            borderColor: 'rgba(75, 192, 192, 1)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderWidth: 2,
            tension: 0.5,
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: true,
                position: 'center'
            }
        },
        scales: {
            x: {
                title: {
                    display: true,
                }
            },
            y: {
                title: {
                    display: true,
                },
                beginAtZero: true
            }
        }
    }
});
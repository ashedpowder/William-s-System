document.querySelector('.btn-report').addEventListener('click', () => {
    const reportContent = `
        Sales Report
        -----------------
        Net Profit: ₱ 30,500.00
        Products Sold: 300
        Transactions: 2,500.00
        Customers: 950
    `;

    const blob = new Blob([reportContent], { type: 'text/plain' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = 'sales_report.txt';
    link.click();
    URL.revokeObjectURL(link.href);
    });

    function downloadChart() {
    const canvas = document.getElementById('netProfitChart');
    const link = document.createElement('a');
    link.href = canvas.toDataURL('image/png');
    link.download = 'net_profit_chart.png';
    link.click();
    }

    document.querySelector('.btn-report').addEventListener('click', downloadChart);
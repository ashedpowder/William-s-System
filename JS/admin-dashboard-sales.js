  // Sales Overview Bar Chart
  const salesCtx = document.getElementById('salesChart').getContext('2d');
  const salesChart = new Chart(salesCtx, {
    type: 'bar',
    data: {
      labels: ['January', 'February', 'March', 'April', 'May', 'June',
       'July', 'August', 'September', 
      'October', 'November', 'December'],
      datasets: [{
        label: 'Sales',
        data: [12000, 19000, 3000, 5000, 20000, 30000, 25000, 40000, 35000, 45000, 50000, 60000],
        backgroundColor: 'rgba(75, 192, 192, 0.2)',
        borderColor: 'rgba(75, 192, 192, 1)',
        borderWidth: 2,
        borderRadius: 6,
        hoverBackgroundColor: 'rgba(75, 192, 192, 0.4)',
        hoverBorderColor: 'rgba(75, 192, 192, 1)',
        hoverBorderWidth: 3,
        barPercentage: 1,
      }]
    },
    options: {
      responsive: true,
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });

  // Top Selling Categories Pie Chart
  const categoriesCtx = document.getElementById('categoriesChart').getContext('2d');
  const categoriesChart = new Chart(categoriesCtx, {
    type: 'pie',
    data: {
      labels: ['Basketball Equipment', 'Volleyball Equipment', 'Acoustic Guitars', 
      'Electric Guitars', 'Bass Guitars', 'Ukeleles', 'String and Components', 'Amplifiers'],
      datasets: [{
        data: [30, 20, 25, 15, 10, 5, 35, 40,],
        backgroundColor: [
          'rgba(255, 99, 132, 0.2)',
          'rgba(54, 162, 235, 0.2)',
          'rgba(255, 206, 86, 0.2)',
          'rgba(75, 192, 192, 0.2)',
          'rgba(153, 102, 255, 0.2)',
        ],
        borderColor: [
          'rgba(255, 99, 132, 2)',
          'rgba(54, 162, 235, 2)',
          'rgba(255, 206, 86, 2)',
          'rgba(75, 192, 192, 2)',
          'rgba(153, 102, 255, 2)',

        ],
        borderWidth: 2,
        borderRadius: 6,
      }]
    },
    options: {
      responsive: true
    }
  });
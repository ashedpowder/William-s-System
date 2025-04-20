const customerData = [
    {name: 'Nikolas Gaurent', email: 'nikolasgaurent@gmail.com', date: 'Mar 9, 2025', amount: '₱8,550.00', status: 'Completed', avatar: 'https://i.pravatar.cc/32?img=1'},
    {name: 'Amakin Gakson', email: 'amakingakson@gmail.com', date: 'Mar 9, 2025', amount: '₱10,000.00', status: 'In Progress', avatar: 'https://i.pravatar.cc/32?img=2'},
    {name: 'Maink Rajkin', email: 'mainkrajkin@gmail.com', date: 'Mar 9, 2025', amount: '₱7,500.00', status: 'Hold', avatar: 'https://i.pravatar.cc/32?img=3'},
    {name: 'Ajak Son', email: 'asokson@gmail.com', date: 'Mar 9, 2025', amount: '₱9,500.00', status: 'Completed', avatar: 'https://i.pravatar.cc/32?img=4'},
    {name: 'Nattnil Jemi', email: 'nattniljemi@gmail.com', date: 'Mar 9, 2025', amount: '₱9,750.00', status: 'Completed', avatar: 'https://i.pravatar.cc/32?img=5'},
    {name: 'Ali Islam', email: 'aliislam@gmail.com', date: 'Mar 9, 2025', amount: '₱6,750.00', status: 'In Progress', avatar: 'https://i.pravatar.cc/32?img=6'},
    {name: 'Kami Kam', email: 'kamikam@gmail.com', date: 'Mar 9, 2025', amount: '₱5,750.00', status: 'Hold', avatar: 'https://i.pravatar.cc/32?img=7'},

  ];

  const customerBody = document.getElementById('customerBody');

  function createRow(data) {
    const statusClass = data.status === 'Completed' ? 'completed' : data.status === 'In Progress' ? 'in-progress' : 'hold';
    return `<tr>
      <td class="checkbox-cell"><input type="checkbox"></td>
      <td>
        <div class="customer-info">
          <img src="${data.avatar}" alt="avatar">
          <div>
            <div><strong>${data.name}</strong></div>
            <div style="color: gray; font-size: 11px;">${data.email}</div>
          </div>
        </div>
      </td>
      <td>${data.date}</td>
      <td>${data.amount}</td>
      <td><span class="status-badge ${statusClass}">
        <span class="dot">⬤</span> ${data.status}
      </span></td>
      <td><button class="menu-button">&#8942;</button></td>
    </tr>`;
  }

  customerBody.innerHTML = customerData.map(createRow).join('');

  function searchCustomer() {
    const input = document.getElementById('searchInput').value.toLowerCase();
    const rows = customerBody.getElementsByTagName('tr');
    for (let row of rows) {
      const name = row.cells[1].innerText.toLowerCase();
      row.style.display = name.includes(input) ? '' : 'none';
    }
  }
  document.getElementById('searchInput').addEventListener('input', searchCustomer);

  function toggleAll(source) {
    const checkboxes = customerBody.querySelectorAll('input[type="checkbox"]');
    checkboxes.forEach(cb => cb.checked = source.checked);
  }
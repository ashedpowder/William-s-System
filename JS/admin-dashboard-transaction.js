function filterTransactions() {
    const input = document.getElementById('transSearchInput').value.toLowerCase();
    const table = document.getElementById('transTable');
    const rows = table.getElementsByTagName('tr');
    for (let i = 1; i < rows.length; i++) {
      const cells = rows[i].getElementsByTagName('td');
      const rowText = Array.from(cells).map(cell => cell.textContent.toLowerCase()).join(' ');
      rows[i].style.display = rowText.includes(input) ? '' : 'none';
    }
  }

  function sortTable() {
    const table = document.getElementById('transTable');
    const rows = Array.from(table.rows).slice(1);
    const sortBy = document.querySelector('.trans-sort').value;
    const sortedRows = rows.sort((a, b) => {
      const dateA = new Date(a.cells[1].innerText);
      const dateB = new Date(b.cells[1].innerText);
      return sortBy === 'newest' ? dateB - dateA : dateA - dateB;
    });
    for (const row of sortedRows) {
      table.tBodies[0].appendChild(row);
    }
  }
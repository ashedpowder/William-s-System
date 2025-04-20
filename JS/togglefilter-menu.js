function downloadTable() {
    alert("Download triggered (not implemented)");
  }
  
  function filterStatus(status) {
    const rows = document.querySelectorAll("#ordersTable tbody tr");
    rows.forEach(row => {
      const statusText = row.children[4].textContent.trim();
      row.style.display = statusText === status ? "" : "none";
    });
  }
  
  function setDateFilter(range) {
    alert("Date range set to: " + range + " (functionality not implemented)");
  }

  function toggleFilterMenu() {
    const filterMenu = document.querySelector(".filter-dropdown-orders .dropdown-content-orders");
    if (filterMenu.style.display === "block") {
      filterMenu.style.display = "none";
    } else {
      filterMenu.style.display = "block";
    }
  }
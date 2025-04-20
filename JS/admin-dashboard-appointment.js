const appointments = {
    '2025-05-02': [{ name: 'Isagi Yoichi', time: '10:00 am - 11:00 am' }],
    '2025-05-04': [{ name: 'Nagi Seishiro', time: '10:00 am - 11:00 am' }],
    '2025-05-05': [{ name: 'Peter', time: '10:00 am - 11:00 am' }],
    '2025-05-06': [{ name: 'Alexandro Bernard', time: '10:00 am - 11:00 am' }],
    '2025-05-13': [{ name: 'Kaiser Brown', time: '10:00 am - 11:00 am' }],
    '2025-05-17': [{ name: 'Romanov Ely', time: '10:00 am - 11:00 am' }],
    '2025-05-18': [{ name: 'Kitty Lily', time: '10:00 am - 11:00 am' }],
    '2025-05-25': [{ name: 'Shasa Brown', time: '10:00 am - 11:00 am' }],
    '2025-05-30': [{ name: 'Budiman Dio', time: '10:00 am - 11:00 am' }],
  };

  function generateCalendar(year, month) {
    const calendarBody = document.getElementById('calendarBody');
    calendarBody.innerHTML = '';

    const firstDay = new Date(year, month, 1);
    const lastDay = new Date(year, month + 1, 0);
    const startDay = firstDay.getDay();
    let date = 1;

    for (let i = 0; i < 6; i++) {
      const row = document.createElement('tr');

      for (let j = 0; j < 7; j++) {
        const cell = document.createElement('td');
        if (i === 0 && j < startDay) {
          cell.innerHTML = '';
        } else if (date > lastDay.getDate()) {
          break;
        } else {
          const currentDate = `${year}-${String(month + 1).padStart(2, '0')}-${String(date).padStart(2, '0')}`;
          cell.innerHTML = `<div>${date}</div>`;

          if (appointments[currentDate]) {
            appointments[currentDate].forEach(appt => {
              const apptDiv = document.createElement('div');
              apptDiv.className = 'app-appointment';
              apptDiv.innerText = `${appt.time}\n${appt.name}`;
              cell.appendChild(apptDiv);
            });
          }

          date++;
        }
        row.appendChild(cell);
      }
      calendarBody.appendChild(row);
    }
  }

  let currentYear = 2025;
  let currentMonth = 4; // May (0-indexed)

  function prevMonth() {
    currentMonth--;
    if (currentMonth < 0) {
      currentMonth = 11;
      currentYear--;
    }
    updateCalendar();
  }

  function nextMonth() {
    currentMonth++;
    if (currentMonth > 11) {
      currentMonth = 0;
      currentYear++;
    }
    updateCalendar();
  }

  function updateCalendar() {
    const monthNames = [
      'January', 'February', 'March', 'April', 'May', 'June',
      'July', 'August', 'September', 'October', 'November', 'December'
    ];
    document.getElementById('monthLabel').innerText = `${monthNames[currentMonth]} ${currentYear}`;
    generateCalendar(currentYear, currentMonth);
  }

  updateCalendar();
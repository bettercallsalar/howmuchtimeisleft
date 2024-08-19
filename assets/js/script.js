function updateTime() {
  seconds++;
  if (seconds === 60) {
    minutes++;
    seconds = 0;
  }
  if (minutes === 60) {
    hours++;
    minutes = 0;
  }
  if (hours === 24) {
    days++;
    hours = 0;
  }

  weeks = Math.floor(days / 7);
  months = Math.floor(days / 30.44);
  years = Math.floor(days / 365.25);

  document.getElementById("seconds").textContent = seconds;
  document.getElementById("minutes").textContent = minutes;
  document.getElementById("hours").textContent = hours;
  document.getElementById("days").textContent = days;
  document.getElementById("weeks").textContent = weeks;
  document.getElementById("months").textContent = months;
  document.getElementById("years").textContent = years;
}

setInterval(updateTime, 1000);

// admin page script
function filterTable() {
  const input = document.getElementById("searchInput");
  const filter = input.value.toLowerCase();
  const table = document.getElementById("userTable");
  const tr = table.getElementsByTagName("tr");

  for (let i = 1; i < tr.length; i++) {
    const tdArray = tr[i].getElementsByTagName("td");
    let matchFound = false;

    for (let j = 0; j < tdArray.length; j++) {
      const td = tdArray[j];
      if (td) {
        if (td.textContent.toLowerCase().includes(filter)) {
          matchFound = true;
          break;
        }
      }
    }

    tr[i].style.display = matchFound ? "" : "none";
  }
}

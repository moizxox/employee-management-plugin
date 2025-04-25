document.addEventListener("DOMContentLoaded", function () {
  if (typeof employeeData === "undefined" || !employeeData.length) {
    return;
  }

  const positions = {};
  const salaryRanges = {
    "0-30k": 0,
    "30k-50k": 0,
    "50k-70k": 0,
    "70k-100k": 0,
    "100k+": 0,
  };
  const hiringByYear = {};

  employeeData.forEach((employee) => {
    if (employee.position) {
      positions[employee.position] = (positions[employee.position] || 0) + 1;
    }

    const salary = parseFloat(employee.salary) || 0;
    if (salary <= 30000) {
      salaryRanges["0-30k"]++;
    } else if (salary <= 50000) {
      salaryRanges["30k-50k"]++;
    } else if (salary <= 70000) {
      salaryRanges["50k-70k"]++;
    } else if (salary <= 100000) {
      salaryRanges["70k-100k"]++;
    } else {
      salaryRanges["100k+"]++;
    }

    if (employee.date_of_hire) {
      const hireDate = new Date(employee.date_of_hire);
      if (!isNaN(hireDate.getTime())) {
        const year = hireDate.getFullYear();
        hiringByYear[year] = (hiringByYear[year] || 0) + 1;
      }
    }
  });

  const sortedYears = Object.keys(hiringByYear).sort();

  // distribution chart
  const salaryCtx = document.getElementById("salaryChart").getContext("2d");
  new Chart(salaryCtx, {
    type: "pie",
    data: {
      labels: Object.keys(salaryRanges),
      datasets: [
        {
          data: Object.values(salaryRanges),
          backgroundColor: [
            "#4e73df",
            "#1cc88a",
            "#36b9cc",
            "#f6c23e",
            "#e74a3b",
          ],
          borderWidth: 1,
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: "right",
        },
        tooltip: {
          callbacks: {
            label: function (context) {
              const label = context.label || "";
              const value = context.raw || 0;
              const total = context.dataset.data.reduce((a, b) => a + b, 0);
              const percentage = Math.round((value / total) * 100);
              return `${label}: ${value} (${percentage}%)`;
            },
          },
        },
      },
    },
  });

  const positionCtx = document.getElementById("positionChart").getContext("2d");
  new Chart(positionCtx, {
    type: "bar",
    data: {
      labels: Object.keys(positions),
      datasets: [
        {
          label: "Number of Employees",
          data: Object.values(positions),
          backgroundColor: "#4e73df",
          borderWidth: 1,
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            precision: 0,
          },
        },
      },
    },
  });

  const hiringCtx = document.getElementById("hiringChart").getContext("2d");
  new Chart(hiringCtx, {
    type: "line",
    data: {
      labels: sortedYears,
      datasets: [
        {
          label: "Employees Hired",
          data: sortedYears.map((year) => hiringByYear[year]),
          borderColor: "#1cc88a",
          backgroundColor: "rgba(28, 200, 138, 0.1)",
          borderWidth: 2,
          fill: true,
          tension: 0.1,
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            precision: 0,
          },
        },
      },
    },
  });
});

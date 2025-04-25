document.addEventListener("DOMContentLoaded", function () {
  const avgSalaryContainer = document.getElementById(
    "average-salary-container"
  );

  if (!avgSalaryContainer) return;

  function fetchAverageSalary() {
    avgSalaryContainer.innerHTML =
      '<span class="loading">Calculating...</span>';

    const xhr = new XMLHttpRequest();
    xhr.open("POST", ajaxurl, true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onload = function () {
      if (this.status === 200) {
        try {
          const response = JSON.parse(this.responseText);
          if (response.success) {
            const formattedSalary = new Intl.NumberFormat("en-US", {
              style: "currency",
              currency: "USD",
              minimumFractionDigits: 2,
            }).format(response.data.average);

            avgSalaryContainer.innerHTML = `<span class="average-value">${formattedSalary}</span>`;
          } else {
            avgSalaryContainer.innerHTML =
              '<span class="error">Error calculating average</span>';
          }
        } catch (e) {
          avgSalaryContainer.innerHTML =
            '<span class="error">Error processing response</span>';
        }
      } else {
        avgSalaryContainer.innerHTML =
          '<span class="error">Request failed</span>';
      }
    };

    xhr.onerror = function () {
      avgSalaryContainer.innerHTML = '<span class="error">Network error</span>';
    };

    xhr.send("action=calculate_average_salary&security=" + avgSalaryNonce);
  }

  fetchAverageSalary();

  const refreshBtn = document.getElementById("refresh-average");
  if (refreshBtn) {
    refreshBtn.addEventListener("click", function (e) {
      e.preventDefault();
      fetchAverageSalary();
    });
  }
});

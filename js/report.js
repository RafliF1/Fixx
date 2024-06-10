function applyView() {
  var view = document.getElementById("view").value;
  window.location.href =
    "report.php?view=" +
    view +
    "&start_date=" +
    document.getElementById("start_date").value +
    "&end_date=" +
    document.getElementById("end_date").value;
}

const dailySalesCtx = document
  .getElementById("dailySalesChart")
  .getContext("2d");
const weeklySalesCtx = document
  .getElementById("weeklySalesChart")
  .getContext("2d");
const monthlySalesCtx = document
  .getElementById("monthlySalesChart")
  .getContext("2d");

const dailySalesData = {
  labels: dailyLabels,
  datasets: [
    {
      label: "Penjualan Harian",
      data: dailyData,
      backgroundColor: "rgba(75, 192, 192, 0.2)",
      borderColor: "rgba(0, 0, 0, 1)",
      borderWidth: 1,
    },
  ],
};

const dailySalesChart = new Chart(dailySalesCtx, {
  type: "line",
  data: dailySalesData,
  options: {
    scales: {
      y: {
        beginAtZero: true,
      },
    },
  },
});

const weeklySalesData = {
  labels: weeklyLabels,
  datasets: [
    {
      label: "Penjualan Mingguan",
      data: weeklyData,
      backgroundColor: "rgba(54, 162, 235, 0.2)",
      borderColor: "rgba(0, 0, 0, 1)",
      borderWidth: 1,
    },
  ],
};

const weeklySalesChart = new Chart(weeklySalesCtx, {
  type: "bar",
  data: weeklySalesData,
  options: {
    scales: {
      y: {
        beginAtZero: true,
      },
    },
  },
});

const monthlySalesData = {
  labels: monthlyLabels,
  datasets: [
    {
      label: "Penjualan Bulanan",
      data: monthlyData,
      backgroundColor: "rgba(255, 0, 0, 0.5)",
      borderColor: "rgba(0, 0, 0, 1)",
      borderWidth: 1,
    },
  ],
};

const monthlySalesChart = new Chart(monthlySalesCtx, {
  type: "bar",
  data: monthlySalesData,
  options: {
    scales: {
      y: {
        beginAtZero: true,
      },
    },
  },
});

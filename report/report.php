<?php
include '../db/db_connect.php';

// Inisialisasi variabel untuk filter tanggal
$startDate = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$endDate = isset($_GET['end_date']) ? $_GET['end_date'] : '';

// Query untuk mengambil data penjualan yang dikelompokkan berdasarkan tanggal
$query = "SELECT DATE(order_date) as order_date, products.name, transactions.quantity, transactions.total_price, transactions.order_type 
          FROM transactions 
          INNER JOIN products ON transactions.product_id = products.id";

if ($startDate && $endDate) {
    $query .= " WHERE DATE(order_date) BETWEEN '$startDate' AND '$endDate'";
}

$query .= " ORDER BY order_date";

$result = mysqli_query($conn, $query);

// Mengelompokkan data berdasarkan tanggal
$salesData = array();
while ($row = mysqli_fetch_assoc($result)) {
    $date = $row['order_date'];
    if (!isset($salesData[$date])) {
        $salesData[$date] = array();
    }
    $salesData[$date][] = $row;
}

// Data penjualan harian
$dailySales = $conn->query("SELECT DATE(order_date) as date, SUM(total_price) as total FROM transactions GROUP BY DATE(order_date)");
$dailyLabels = [];
$dailyData = [];
while ($row = $dailySales->fetch_assoc()) {
    $dailyLabels[] = $row['date'];
    $dailyData[] = $row['total'];
}

// Data penjualan mingguan
$weeklySales = $conn->query("SELECT WEEK(order_date) as week, SUM(total_price) as total FROM transactions GROUP BY WEEK(order_date)");
$weeklyLabels = [];
$weeklyData = [];
while ($row = $weeklySales->fetch_assoc()) {
    $weeklyLabels[] = 'Week ' . $row['week'];
    $weeklyData[] = $row['total'];
}

// Data penjualan bulanan
$monthlySales = $conn->query("SELECT MONTH(order_date) as month, SUM(total_price) as total FROM transactions GROUP BY MONTH(order_date)");
$monthlyLabels = [];
$monthlyData = [];
while ($row = $monthlySales->fetch_assoc()) {
    $monthlyLabels[] = 'Month ' . $row['month'];
    $monthlyData[] = $row['total'];
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Laporan Penjualan</title>
    <link rel="stylesheet" type="text/css" href="../css/report.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <h1>Laporan Penjualan</h1>
    <nav>
        <a href="../index/index.php">Daftar Produk</a> |
        <a href="../order/order.php">Form Pesanan</a> |
        <a href="report.php">Laporan Penjualan</a> |
        <a href="../stock/stock_management.php">Manajemen Stok</a>
    </nav>

    <h2>Penjualan Harian</h2>
    <canvas id="dailySalesChart"></canvas>
    <h2>Penjualan Mingguan</h2>
    <canvas id="weeklySalesChart"></canvas>
    <h2>Penjualan Bulanan</h2>
    <canvas id="monthlySalesChart"></canvas>

    <h2>Laporan Penjualan Tabel</h2>
    <!-- Form Pencarian -->
    <form method="GET" action="report.php">
        <label for="start_date">Tanggal Mulai:</label>
        <input type="date" id="start_date" name="start_date" value="<?php echo $startDate; ?>">
        <label for="end_date">Tanggal Akhir:</label>
        <input type="date" id="end_date" name="end_date" value="<?php echo $endDate; ?>">
        <button type="submit">Cari</button>
    </form>

    <!-- Pilihan tampilan -->
    <label for="view">Pilih Tampilan:</label>
    <select id="view" name="view" onchange="applyView()">
        <option value="simple">Tampilan Sederhana</option>
        <option value="detail">Detail Pesanan</option>
    </select>
    <button onclick="applyView()">Terapkan</button>

    <!-- Konten -->
    <div class="content">
        <?php
        // Menentukan tampilan yang dipilih
        $view = isset($_GET['view']) ? $_GET['view'] : 'simple';

        if ($view === 'simple') {
            // Tampilan sederhana
            echo "<h2>Laporan Penjualan (Tampilan Sederhana)</h2>";
            echo "<table>";
            echo "<tr><th>Tanggal</th><th>Total Penjualan</th></tr>";
            $simpleQuery = "SELECT DATE(order_date) AS order_date, SUM(total_price) AS total_sales FROM transactions";
            if ($startDate && $endDate) {
                $simpleQuery .= " WHERE DATE(order_date) BETWEEN '$startDate' AND '$endDate'";
            }
            $simpleQuery .= " GROUP BY DATE(order_date)";
            $simpleResult = mysqli_query($conn, $simpleQuery);
            while ($row = mysqli_fetch_assoc($simpleResult)) {
                echo "<tr>";
                echo "<td>{$row['order_date']}</td>";
                echo "<td>{$row['total_sales']}</td>";
                echo "</tr>";
            }
            echo "</table>";
        } elseif ($view === 'detail') {
            // Tampilan detail
            echo "<h2>Laporan Penjualan (Detail Pesanan)</h2>";
            echo "<table>";
            echo "<tr><th>Tanggal</th><th>Produk</th><th>Jumlah</th><th>Total Harga</th><th>Tipe Pesanan</th>";

            foreach ($salesData as $date => $sales) {
                echo "<tr><td colspan='5'><strong>$date</strong></td></tr>";

                foreach ($sales as $sale) {
                    echo "<tr>";
                    echo "<td></td>";
                    echo "<td>{$sale['name']}</td>";
                    echo "<td>{$sale['quantity']}</td>";
                    echo "<td>{$sale['total_price']}</td>";
                    echo "<td>{$sale['order_type']}</td>";
                    echo "</tr>";
                }
            }
            echo "</table>";
        }
        ?>
    </div>

    <script>
    const dailyLabels = <?php echo json_encode($dailyLabels); ?>;
    const dailyData = <?php echo json_encode($dailyData); ?>;
    const weeklyLabels = <?php echo json_encode($weeklyLabels); ?>;
    const weeklyData = <?php echo json_encode($weeklyData); ?>;
    const monthlyLabels = <?php echo json_encode($monthlyLabels); ?>;
    const monthlyData = <?php echo json_encode($monthlyData); ?>;
    </script>
    <script src="../js/report.js"></script>
</body>

</html>
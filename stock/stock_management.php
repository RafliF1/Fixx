<!DOCTYPE html>
<html>

<head>
    <title>Manajemen Stok</title>
    <link rel="stylesheet" type="text/css" href="../css/stock.css">
</head>

<body>
    <h1>Manajemen Stok</h1>
    <nav>
        <a href="../index/index.php">Daftar Produk</a>|
        <a href="../order/order.php"> Form Pesanan</a>|
        <a href="../report/report.php"> Laporan Penjualan</a>|
        <a href="stock_management.php"> Manajemen Stok</a>
    </nav>

    <!-- Menambah Stok Barang -->
    <h2>Tambah Stok</h2>
    <form action="add_stock.php" method="post">
        <label for="product">Produk:</label>
        <select id="product" name="product" required>
            <?php
            include '../db/db_connect.php';
            // Only select products that are not deleted
            $result = mysqli_query($conn, "SELECT * FROM products WHERE is_deleted = 0");
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<option value='{$row['id']}'>{$row['name']}</option>";
            }
            ?>
        </select><br>
        <label for="quantity">Jumlah:</label>
        <input type="number" id="quantity" name="quantity" min="1" value="1" required><br>
        <input type="submit" value="Tambah Stok">
    </form>

    <h2>Stok Produk</h2>
    <table>
        <tr>
            <th>Produk</th>
            <th>Stok</th>
        </tr>

        <!-- Menampilkan data produk dan stok dalam tabel -->
        <?php
        // Only select products and stock where products are not deleted
        $result = mysqli_query($conn, "SELECT p.name AS product_name, s.stock FROM products p INNER JOIN stock s ON p.id = s.product_id WHERE p.is_deleted = 0");
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>{$row['product_name']}</td>";
            echo "<td>{$row['stock']}</td>";
            echo "</tr>";
        }
        ?>
    </table>
</body>

</html>
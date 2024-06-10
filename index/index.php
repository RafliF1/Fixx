<?php
include '../db/db_connect.php';
$result = mysqli_query($conn, "SELECT * FROM products WHERE is_deleted = 0");
?>

<!DOCTYPE html>
<html>

<head>
    <title>Daftar Produk</title>
    <link rel="stylesheet" type="text/css" href="../css/indexs.css">
</head>

<body>
    <h1>Daftar Produk</h1>
    <nav>
        <a href="index.php">Daftar Produk</a>|
        <a href="../order/order.php">Form Pesanan</a>|
        <a href="../report/report.php">Laporan Penjualan</a>|
        <a href="../stock/stock_management.php">Manajemen Stok</a>
    </nav>
    <table>
        <tr>
            <th>Nama Produk</th>
            <th>Jenis Produk</th>
            <th>Harga</th>
            <th>Aksi</th>
        </tr>
        <?php
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>{$row['name']}</td>";
            echo "<td>{$row['type']}</td>";
            echo "<td>Rp." . number_format($row['price'], 2, ',', '.') . "</td>";
            echo "<td><a href='../product/edit_product.php?id={$row['id']}'>Edit</a> | <a href='../product/delete_product.php?id={$row['id']}'>Hapus</a></td>";
            echo "</tr>";
        }
        ?>
    </table>
    <a href="../product/add_product.php" class="add-button">Tambah Produk</a>
</body>

</html>
<?php
session_start();

if (isset($_GET['total_order_price']) && isset($_GET['payment']) && isset($_GET['change']) && isset($_SESSION['order_details'])) {
    $total_order_price = $_GET['total_order_price'];
    $payment = $_GET['payment'];
    $change = $_GET['change'];
    $order_details = $_SESSION['order_details'];
    unset($_SESSION['order_details']); // Clear temporary session data
} else {
    header('Location: order.php');
    exit();
} 
?>
<!DOCTYPE html>
<html>

<head>
    <title>Struk Pembayaran</title>
    <link rel="stylesheet" type="text/css" href="../css/order_sukses.css">
</head>

<body>
    <a href="../index/index.php" class="back-button">Kembali</a>
    <h1>Struk Pembayaran</h1>

    <div class="container">
        <h2>Detail Pembayaran</h2>
        <p>Total Harga Pesanan: Rp. <?php echo number_format($total_order_price, 2, ',', '.'); ?></p>
        <p>Pembayaran: Rp. <?php echo number_format($payment, 2, ',', '.'); ?></p>
        <p>Kembalian: Rp. <?php echo number_format($change, 2, ',', '.'); ?></p>

        <h2>Detail Pesanan</h2>
        <table>
            <tr>
                <th>Produk</th>
                <th>Jumlah</th>
                <th>Total Harga</th>
                <th>Tipe Pesanan</th>
            </tr>
            <?php
            if (!empty($order_details)) {
                foreach ($order_details as $order) {
                    echo "<tr>";
                    echo "<td>{$order['product_name']}</td>";
                    echo "<td>{$order['quantity']}</td>";
                    echo "<td>Rp." . number_format($order['total_price'], 2, ',', '.') . "</td>";
                    echo "<td>{$order['order_type']}</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>Tidak ada pesanan.</td></tr>";
            }
            ?>
        </table>
    </div>
</body>

</html>
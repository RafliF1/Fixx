<?php
session_start();
include '../db/db_connect.php';

// Inisialisasi total harga pesanan jika belum ada
if (!isset($_SESSION['total_order_price'])) {
    $_SESSION['total_order_price'] = 0;
}

// Proses penambahan pesanan
if (isset($_POST['product'], $_POST['quantity'], $_POST['order_type'])) {
    $product_id = $_POST['product'];
    $quantity = $_POST['quantity'];
    $order_type = $_POST['order_type'];

    // Ambil informasi produk dari database
    $sql = "SELECT * FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    $stmt->close();

    // Hitung total harga pesanan
    $total_price = $product['price'] * $quantity;

    // Simpan pesanan ke dalam session
    if (!isset($_SESSION['order'])) {
        $_SESSION['order'] = [];
    }
    $_SESSION['order'][] = [
        'product_id' => $product_id,
        'product_name' => $product['name'],
        'quantity' => $quantity,
        'total_price' => $total_price,
        'order_type' => $order_type
    ];

    // Update total harga pesanan
    $_SESSION['total_order_price'] += $total_price;

    header('Location: order.php?success=Pesanan berhasil ditambahkan');
    exit();
} 

?>
<!DOCTYPE html>
<html>

<head>
    <title>Form Pesanan</title>
    <link rel="stylesheet" type="text/css" href="../css/order.css">
</head>

<body>
    <h1>Form Pesanan</h1>
    <nav>
        <a href="../index/index.php">Daftar Produk</a>|
        <a href="order.php"> Form Pesanan</a>|
        <a href="../report/report.php"> Laporan Penjualan</a>|
        <a href="../stock/stock_management.php"> Manajemen Stok</a>
    </nav>

    <?php
    if (isset($_GET['error'])) {
        echo "<p class='error-message'>{$_GET['error']}</p>";
    }
    if (isset($_GET['success'])) {
        echo "<p class='success-message'>{$_GET['success']}</p>";
    }
    if (isset($_GET['change'])) {
        echo "<p class='success-message'>Kembalian: Rp. " . number_format($_GET['change'], 2, ',', '.') . "</p>";
    }
    ?>

    <form action="order.php" method="post">
        <label for="product">Produk:</label>
        <select id="product" name="product" required>
            <?php
            $result = mysqli_query($conn, "SELECT * FROM products WHERE is_deleted = 0");
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<option value='{$row['id']}'>{$row['name']} - {$row['type']} - Rp.{$row['price']}</option>";
            }
            ?>
        </select><br>
        <label for="quantity">Jumlah:</label>
        <input type="number" id="quantity" name="quantity" min="1" value="1" required><br>
        <label for="order_type">Tipe Pesanan:</label>
        <select id="order_type" name="order_type" required>
            <option value="Bawa Pulang">Bawa Pulang</option>
            <option value="Makan Ditempat">Makan Ditempat</option>
        </select><br>
        <input type="submit" value="Tambah">
    </form>

    <?php
    if (isset($_SESSION['order'])) {
        echo "<h2>Daftar Pesanan</h2>";
        echo "<table><tr><th>Produk</th><th>Jumlah</th><th>Total Harga</th><th>Tipe Pesanan</th><th>Aksi</th></tr>";
        foreach ($_SESSION['order'] as $index => $order) {
            echo "<tr>";
            echo "<td>{$order['product_name']}</td>";
            echo "<td>{$order['quantity']}</td>";
            echo "<td>Rp." . number_format($order['total_price'], 2, ',', '.') . "</td>";
            echo "<td>{$order['order_type']}</td>";
            echo "<td><a href='edit_order.php?action=reduce&index={$index}'>Kurangi</a> | <a href='edit_order.php?action=remove&index={$index}'>Hapus</a></td>";
            echo "</tr>";
        }
        echo "</table>";
        
        // Tampilkan form pembayaran
        echo "<form action='confirm_order.php' method='post'>";
        echo "<label for='payment'>Total Pesanan:</label>";
        echo "<input type='text' id='total_order' name='total_order' value='Rp. " . number_format($_SESSION['total_order_price'], 2, ',', '.') . "' readonly><br>";
        echo "<label for='payment'>Pembayaran:</label>";
        echo "<input type='number' id='payment' name='payment' min='" . $_SESSION['total_order_price'] . "' required><br>";
        echo "<input type='submit' value='Konfirmasi Pesanan'>";
        echo "</form>";
        
    }
    ?>
</body>

</html>
<?php
session_start();
include '../db/db_connect.php';

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

    // Redirect pengguna kembali ke halaman order.php
    header('Location: order.php');
    exit();
} else {
    echo "Product, quantity, and order type are required.";
}
?>
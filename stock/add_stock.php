<?php
include '../db/db_connect.php';

$product_id = $_POST['product'];
$quantity = $_POST['quantity'];

// Ambil stok saat ini
$current_stock_result = mysqli_query($conn, "SELECT stock FROM stock WHERE product_id=$product_id");
$current_stock_row = mysqli_fetch_assoc($current_stock_result);
$current_stock = $current_stock_row ? $current_stock_row['stock'] : 0;

// Hitung jumlah stok baru
$new_stock = $current_stock + $quantity;

// Periksa apakah produk sudah ada dalam tabel stok
$product_exists = mysqli_query($conn, "SELECT COUNT(*) AS count FROM stock WHERE product_id=$product_id");
$product_exists_row = mysqli_fetch_assoc($product_exists);
$count = $product_exists_row ? $product_exists_row['count'] : 0;

if ($count > 0) {
    // Update stok jika produk sudah ada dalam tabel stok
    $sql = "UPDATE stock SET stock=$new_stock WHERE product_id=$product_id";
} else {
    // Tambahkan stok baru jika produk belum ada dalam tabel stok
    $sql = "INSERT INTO stock (product_id, stock) VALUES ($product_id, $new_stock)";
}

if (mysqli_query($conn, $sql)) {
    echo "Stok berhasil ditambahkan.";
    header("Location: ../stock/stock_management.php");
    exit();
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

mysqli_close($conn);
?>
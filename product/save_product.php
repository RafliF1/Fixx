<?php
include '../db/db_connect.php';

$name = $_POST['name'];
$type = $_POST['type'];
$price = $_POST['price'];

$sql = "INSERT INTO products (name, type, price) VALUES ('$name', '$type', '$price')";
if (mysqli_query($conn, $sql)) {
    echo "Produk berhasil ditambahkan.";
    header("Location: ../index/index.php");
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

mysqli_close($conn);
?>
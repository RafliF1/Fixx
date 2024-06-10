<?php
include '../db/db_connect.php';

$id = $_POST['id'];
$name = $_POST['name'];
$type = $_POST['type'];
$price = $_POST['price'];

$sql = "UPDATE products SET name='$name', type='$type', price='$price' WHERE id=$id";
if (mysqli_query($conn, $sql)) {
    echo "Produk berhasil diupdate.";
    header("Location: ../index/index.php");
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

mysqli_close($conn);
?>
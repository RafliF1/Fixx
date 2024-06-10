<?php
//Detail Koneksi
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "toko_sate";

// Membuat Koneksi
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Cek Koneksi
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
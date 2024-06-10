<!DOCTYPE html>
<html>

<head>
    <title>Tambah Produk</title>
    <link rel="stylesheet" type="text/css" href="../css/indexs.css">
</head>

<body>

    <a href="../index/index.php" class="back-button">Kembali</a>

    <h1>Tambah Produk Baru</h1>
    <form action="save_product.php" method="post">
        <label for="name">Nama Produk:</label>
        <input type="text" id="name" name="name" required><br>
        <label for="type">Tipe:</label>
        <select id="type" name="type" required>
            <option value="Makanan">Makanan</option>
            <option value="Minuman">Minuman</option>
        </select><br>
        <label for="price">Harga:</label>
        <input type="text" id="price" name="price" required><br>
        <input type="submit" value="Simpan">
    </form>

</body>

</html>
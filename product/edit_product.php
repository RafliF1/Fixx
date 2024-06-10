<!DOCTYPE html>
<html>

<head>
    <title>Edit Produk</title>
    <link rel="stylesheet" type="text/css" href="../css/indexs.css">
</head>

<body>
    <a href="../index/index.php" class="back-button">Kembali</a>

    <h1>Edit Produk</h1>
    <?php
    include '../db/db_connect.php';
    $id = $_GET['id'];
    $result = mysqli_query($conn, "SELECT * FROM products WHERE id=$id");
    $row = mysqli_fetch_assoc($result);
    ?>
    <form action="update_product.php" method="post">
        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
        <label for="name">Nama Produk:</label>
        <input type="text" id="name" name="name" value="<?php echo $row['name']; ?>" required><br>
        <label for="type">Tipe:</label>
        <select id="type" name="type" required>
            <option value="Makanan" <?php if($row['type'] == 'Makanan') echo 'selected'; ?>>Makanan</option>
            <option value="Minuman" <?php if($row['type'] == 'Minuman') echo 'selected'; ?>>Minuman</option>
        </select><br>
        <label for="price">Harga:</label>
        <input type="text" id="price" name="price" value="<?php echo $row['price']; ?>" required><br>
        <input type="submit" value="Update">
    </form>
</body>

</html>
<?php
include '../db/db_connect.php';

// Ensure the product ID is provided
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Begin transaction
    mysqli_begin_transaction($conn);

    try {
        // Soft delete the product
        $sql_delete_product = "UPDATE products SET is_deleted = 1 WHERE id = ?";
        $stmt_delete_product = $conn->prepare($sql_delete_product);
        $stmt_delete_product->bind_param("i", $product_id);
        $stmt_delete_product->execute();
        $stmt_delete_product->close();

        // Soft delete entries related to this product in the stock table
        $sql_delete_stock = "UPDATE stock SET is_deleted = 1 WHERE product_id = ?";
        $stmt_delete_stock = $conn->prepare($sql_delete_stock);
        $stmt_delete_stock->bind_param("i", $product_id);
        $stmt_delete_stock->execute();
        $stmt_delete_stock->close();

        // Soft delete entries related to this product in the transactions table
        $sql_delete_transactions = "UPDATE transactions SET is_deleted = 1 WHERE product_id = ?";
        $stmt_delete_transactions = $conn->prepare($sql_delete_transactions);
        $stmt_delete_transactions->bind_param("i", $product_id);
        $stmt_delete_transactions->execute();
        $stmt_delete_transactions->close();

        // Commit transaction
        mysqli_commit($conn);

        // Close connection
        $conn->close();

        // Redirect back to the product list
        header('Location: ../index/index.php');
        exit();
    } catch (Exception $e) {
        // Rollback transaction if there's an error
        mysqli_rollback($conn);
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Product ID is required.";
}
?>
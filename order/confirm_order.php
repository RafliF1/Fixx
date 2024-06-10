<?php
session_start();
include '../db/db_connect.php';

if (!isset($_SESSION['order'])) {
    header('Location: order.php');
    exit();
}

if (!isset($_POST['payment'])) {
    header('Location: order.php?error=Payment amount is required');
    exit();
}

$payment = $_POST['payment'];

// Begin transaction
mysqli_begin_transaction($conn);

try {
    $total_order_price = 0;
    $order_details = [];

    foreach ($_SESSION['order'] as $order) {
        $product_id = $order['product_id'];
        $quantity = $order['quantity'];

        // Check current stock
        $check_stock_sql = "SELECT stock FROM stock WHERE product_id = ? AND is_deleted = 0";
        $stmt = $conn->prepare($check_stock_sql);
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $current_stock = $row['stock'];

        if ($current_stock < $quantity) {
            throw new Exception("Stok tidak cukup untuk produk: {$order['product_name']}");
        }

        // Reduce stock
        $update_stock_sql = "UPDATE stock SET stock = stock - ? WHERE product_id = ? AND is_deleted = 0";
        $stmt = $conn->prepare($update_stock_sql);
        $stmt->bind_param("ii", $quantity, $product_id);
        $stmt->execute();

        // Add transaction
        $total_price = $order['total_price'];
        $total_order_price += $total_price;
        $order_type = $order['order_type'];
        $insert_transaction_sql = "INSERT INTO transactions (product_id, quantity, total_price, order_type) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_transaction_sql);
        $stmt->bind_param("iids", $product_id, $quantity, $total_price, $order_type);
        $stmt->execute();

        // Save order details for display
        $order_details[] = $order;
    }

    // Commit transaction
    mysqli_commit($conn);

    // Calculate change
    $change = $payment - $total_order_price;

    // Store order details temporarily for success page
    $_SESSION['order_details'] = $order_details;

    // Clear session order
    unset($_SESSION['order']);
    $_SESSION['total_order_price'] = 0;

    // Redirect to order_success.php with details
    header("Location: order_success.php?total_order_price={$total_order_price}&payment={$payment}&change={$change}");
    exit();
} catch (Exception $e) {
    // Rollback transaction if any error occurs
    mysqli_rollback($conn);
    header('Location: order.php?error=' . $e->getMessage());
    exit();
}
?>
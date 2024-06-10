<?php
session_start();

if (!isset($_SESSION['order']) || !isset($_GET['action']) || !isset($_GET['index'])) {
    header('Location: order.php');
    exit();
}

$index = $_GET['index'];
$action = $_GET['action'];

if (!isset($_SESSION['order'][$index])) {
    header('Location: order.php');
    exit();
}

$order = $_SESSION['order'][$index];

switch ($action) {
    case 'reduce':
        if ($order['quantity'] > 1) {
            $_SESSION['order'][$index]['quantity']--;
            $_SESSION['order'][$index]['total_price'] -= $order['total_price'] / $order['quantity'];
        } else {
            unset($_SESSION['order'][$index]);
        }
        break;
    case 'remove':
        unset($_SESSION['order'][$index]);
        break;
    default:
        header('Location: order.php');
        exit();
}

// Update total harga pesanan
$_SESSION['total_order_price'] = 0;
foreach ($_SESSION['order'] as $order) {
    $_SESSION['total_order_price'] += $order['total_price'];
}

header('Location: order.php');
exit();
?>
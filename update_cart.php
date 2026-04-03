<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/auth.php';
require_once 'db/conn.php';

requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: cart.php');
    exit();
}

$cart_id  = (int)$_POST['cart_id'];
$quantity = max(1, (int)$_POST['quantity']);

// Ensure this cart row belongs to the current user and quantity doesn't exceed stock
$stmt = $conn->prepare(
    "UPDATE cart c
     JOIN products p ON c.product_id = p.id
     SET c.quantity = LEAST(?, p.stock)
     WHERE c.id = ? AND c.user_id = ?"
);
$stmt->bind_param("iii", $quantity, $cart_id, $_SESSION['user_id']);
$stmt->execute();
$stmt->close();

header('Location: cart.php?msg=updated');
exit();
?>

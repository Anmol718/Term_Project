<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/auth.php';
require_once 'db/conn.php';

requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: products.php');
    exit();
}

$product_id = (int)($_POST['product_id'] ?? 0);
$quantity   = max(1, (int)($_POST['quantity'] ?? 1));
$redirect   = isset($_POST['redirect']) ? htmlspecialchars($_POST['redirect']) : 'cart.php';
$user_id    = $_SESSION['user_id'];

// Verify product exists, has stock, and isn't user's own listing
$stmt = $conn->prepare("SELECT id, stock, seller_id FROM products WHERE id = ? AND stock > 0");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$product) {
    header("Location: $redirect&msg=unavailable");
    exit();
}
if ((int)$product['seller_id'] === $user_id) {
    header("Location: $redirect&msg=own_listing");
    exit();
}

$quantity = min($quantity, $product['stock']);

// Insert or update cart
$stmt = $conn->prepare(
    "INSERT INTO cart (user_id, product_id, quantity)
     VALUES (?, ?, ?)
     ON DUPLICATE KEY UPDATE quantity = LEAST(quantity + VALUES(quantity), ?)"
);
$stmt->bind_param("iiii", $user_id, $product_id, $quantity, $product['stock']);
$stmt->execute();
$stmt->close();

header("Location: cart.php?added=1");
exit();
?>

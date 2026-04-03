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

$user_id = $_SESSION['user_id'];

// Fetch cart items fresh from DB
$stmt = $conn->prepare(
    "SELECT c.quantity, p.id AS product_id, p.price, p.stock
     FROM cart c
     JOIN products p ON c.product_id = p.id
     WHERE c.user_id = ? AND p.stock > 0"
);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

if (empty($items)) {
    header('Location: cart.php');
    exit();
}

$total = 0;
foreach ($items as $item) {
    $qty    = min($item['quantity'], $item['stock']);
    $total += $item['price'] * $qty;
}

// Fetch shipping address
$stmt = $conn->prepare("SELECT address, city, province, postalcode FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

$shipping = $user['address'] . ', ' . $user['city'] . ', ' . $user['province'] . ' ' . $user['postalcode'];

// Begin transaction
$conn->begin_transaction();
try {
    // Insert order
    $stmt = $conn->prepare(
        "INSERT INTO orders (user_id, total_price, shipping_address) VALUES (?, ?, ?)"
    );
    $stmt->bind_param("ids", $user_id, $total, $shipping);
    $stmt->execute();
    $order_id = $conn->insert_id;
    $stmt->close();

    // Insert order items and decrement stock
    $stmtItem = $conn->prepare(
        "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)"
    );
    $stmtStock = $conn->prepare(
        "UPDATE products SET stock = stock - ? WHERE id = ? AND stock >= ?"
    );

    foreach ($items as $item) {
        $qty = min($item['quantity'], $item['stock']);
        $stmtItem->bind_param("iiid", $order_id, $item['product_id'], $qty, $item['price']);
        $stmtItem->execute();
        $stmtStock->bind_param("iii", $qty, $item['product_id'], $qty);
        $stmtStock->execute();
    }
    $stmtItem->close();
    $stmtStock->close();

    // Clear cart
    $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();

    $conn->commit();
    header('Location: orders.php?new=' . $order_id);
    exit();
} catch (Exception $e) {
    $conn->rollback();
    header('Location: checkout.php?error=Order+failed.+Please+try+again.');
    exit();
}
?>

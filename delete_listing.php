<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/auth.php';
require_once 'db/conn.php';

requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: my_listings.php');
    exit();
}

$product_id = (int)($_POST['product_id'] ?? 0);

// Verify ownership (or admin)
if (isAdmin()) {
    $stmt = $conn->prepare("SELECT id, image FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
} else {
    $stmt = $conn->prepare("SELECT id, image FROM products WHERE id = ? AND seller_id = ?");
    $stmt->bind_param("ii", $product_id, $_SESSION['user_id']);
}
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$product) {
    header('Location: my_listings.php');
    exit();
}

// Remove from carts first
$stmt = $conn->prepare("DELETE FROM cart WHERE product_id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$stmt->close();

// Delete product
$stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$stmt->close();

// Delete image file
if ($product['image'] && file_exists(UPLOADS_DIR . $product['image'])) {
    unlink(UPLOADS_DIR . $product['image']);
}

$back = isAdmin() ? 'admin/products.php?msg=deleted' : 'my_listings.php?msg=deleted';
header("Location: $back");
exit();
?>

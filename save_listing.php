<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/auth.php';
require_once 'db/conn.php';

requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: sell.php');
    exit();
}

$name        = strip_tags(trim($_POST['name'] ?? ''));
$description = strip_tags(trim($_POST['description'] ?? ''));
$price       = (float)($_POST['price'] ?? 0);
$stock       = max(1, (int)($_POST['stock'] ?? 1));
$category    = strip_tags(trim($_POST['category'] ?? ''));
$condition   = strip_tags(trim($_POST['condition'] ?? ''));
$seller_id   = $_SESSION['user_id'];

// Server-side validation
if (!$name || $price <= 0 || !in_array($category, $CATEGORIES) || !in_array($condition, $CONDITIONS)) {
    header('Location: sell.php?error=Please+fill+in+all+required+fields+correctly.');
    exit();
}

// Handle image upload
$imageName = null;
if (!empty($_FILES['image']['name'])) {
    $file     = $_FILES['image'];
    $allowed  = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $maxSize  = 5 * 1024 * 1024; // 5 MB

    if (!in_array($file['type'], $allowed)) {
        header('Location: sell.php?error=Only+JPG,+PNG,+GIF,+and+WebP+images+are+allowed.');
        exit();
    }
    if ($file['size'] > $maxSize) {
        header('Location: sell.php?error=Image+must+be+under+5+MB.');
        exit();
    }

    $ext       = pathinfo($file['name'], PATHINFO_EXTENSION);
    $imageName = uniqid('prod_', true) . '.' . strtolower($ext);
    if (!move_uploaded_file($file['tmp_name'], UPLOADS_DIR . $imageName)) {
        header('Location: sell.php?error=Failed+to+upload+image.+Please+try+again.');
        exit();
    }
}

$stmt = $conn->prepare(
    "INSERT INTO products (name, description, price, image, category, `condition`, stock, seller_id)
     VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
);
$stmt->bind_param("ssdsssii", $name, $description, $price, $imageName, $category, $condition, $stock, $seller_id);

if ($stmt->execute()) {
    $product_id = $conn->insert_id;
    header('Location: product.php?id=' . $product_id . '&listed=1');
    exit();
} else {
    header('Location: sell.php?error=Failed+to+save+listing.+Please+try+again.');
    exit();
}
?>

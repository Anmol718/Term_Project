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

$product_id  = (int)($_POST['product_id'] ?? 0);
$name        = strip_tags(trim($_POST['name'] ?? ''));
$description = strip_tags(trim($_POST['description'] ?? ''));
$price       = (float)($_POST['price'] ?? 0);
$stock       = max(0, (int)($_POST['stock'] ?? 0));
$category    = strip_tags(trim($_POST['category'] ?? ''));
$condition   = strip_tags(trim($_POST['condition'] ?? ''));

// Server-side validation
if (!$name || $price <= 0 || !in_array($category, $CATEGORIES) || !in_array($condition, $CONDITIONS)) {
    header("Location: edit_listing.php?id=$product_id&error=Please+fill+in+all+required+fields+correctly.");
    exit();
}

// Verify ownership (or admin)
if (isAdmin()) {
    $stmt = $conn->prepare("SELECT id, image FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
} else {
    $stmt = $conn->prepare("SELECT id, image FROM products WHERE id = ? AND seller_id = ?");
    $stmt->bind_param("ii", $product_id, $_SESSION['user_id']);
}
$stmt->execute();
$existing = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$existing) {
    header('Location: my_listings.php');
    exit();
}

// Handle new image upload
$imageName = $existing['image'];
if (!empty($_FILES['image']['name'])) {
    $file     = $_FILES['image'];
    $allowed  = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $maxSize  = 5 * 1024 * 1024;

    if (!in_array($file['type'], $allowed) || $file['size'] > $maxSize) {
        header("Location: edit_listing.php?id=$product_id&error=Invalid+or+too+large+image.");
        exit();
    }

    $ext       = pathinfo($file['name'], PATHINFO_EXTENSION);
    $newName   = uniqid('prod_', true) . '.' . strtolower($ext);
    if (move_uploaded_file($file['tmp_name'], UPLOADS_DIR . $newName)) {
        // Delete old image
        if ($imageName && file_exists(UPLOADS_DIR . $imageName)) {
            unlink(UPLOADS_DIR . $imageName);
        }
        $imageName = $newName;
    }
}

$stmt = $conn->prepare(
    "UPDATE products SET name=?, description=?, price=?, image=?, category=?, `condition`=?, stock=?
     WHERE id=?"
);
$stmt->bind_param("ssdsssii", $name, $description, $price, $imageName, $category, $condition, $stock, $product_id);
$stmt->execute();
$stmt->close();

$back = isAdmin() ? 'admin/products.php?msg=updated' : 'my_listings.php?msg=updated';
header("Location: $back");
exit();
?>

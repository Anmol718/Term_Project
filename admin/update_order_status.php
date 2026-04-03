<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../db/conn.php';

requireAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: orders.php');
    exit();
}

$order_id  = (int)$_POST['order_id'];
$status    = trim($_POST['status'] ?? '');
$allowed   = ['Pending', 'Shipped', 'Delivered', 'Cancelled'];

if (!in_array($status, $allowed)) {
    header('Location: orders.php');
    exit();
}

$stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
$stmt->bind_param("si", $status, $order_id);
$stmt->execute();
$stmt->close();

header('Location: orders.php?msg=updated');
exit();
?>

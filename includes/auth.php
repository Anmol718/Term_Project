<?php
function requireLogin($redirect = null) {
    if (!isset($_SESSION['user_id'])) {
        $back = $redirect ?? ($_SERVER['REQUEST_URI'] ?? '');
        $url = BASE_URL . '/loginform.php?error=Please+log+in+to+continue.';
        header('Location: ' . $url);
        exit();
    }
}

function requireAdmin() {
    if (!isset($_SESSION['user_id']) || empty($_SESSION['is_admin'])) {
        header('Location: ' . BASE_URL . '/index.php');
        exit();
    }
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['is_admin']);
}

function cartCount($conn) {
    if (!isLoggedIn()) return 0;
    $uid = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT COALESCE(SUM(quantity),0) FROM cart WHERE user_id = ?");
    $stmt->bind_param("i", $uid);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();
    return (int)$count;
}

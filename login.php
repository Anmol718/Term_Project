<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: loginform.php');
    exit();
}

require_once 'includes/config.php';
require_once 'db/conn.php';

$email    = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
$password = trim($_POST['password']);

$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user && password_verify($password, $user['password'])) {
    session_regenerate_id(true);
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['user_name']  = $user['username'];
    $_SESSION['user_id']    = $user['id'];
    $_SESSION['is_admin']   = (bool)$user['is_admin'];

    if ($user['is_admin']) {
        header('Location: admin/index.php');
    } else {
        header('Location: index.php');
    }
    exit();
} else {
    header('Location: loginform.php?error=Invalid+email+or+password.');
    exit();
}
?>

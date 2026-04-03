<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: signupform.php');
    exit();
}

require_once 'db/conn.php';

$firstname    = strip_tags(trim($_POST['firstname']));
$lastname     = strip_tags(trim($_POST['lastname']));
$username     = strip_tags(trim($_POST['username']));
$email        = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
$password     = trim($_POST['password']);
$confirm_pass = trim($_POST['confirm_password']);
$address      = strip_tags(trim($_POST['address']));
$city         = strip_tags(trim($_POST['city']));
$province     = strip_tags(trim($_POST['province']));
$postalcode   = strip_tags(trim($_POST['postalcode']));

$firstname  = htmlspecialchars($firstname);
$lastname   = htmlspecialchars($lastname);
$username   = htmlspecialchars($username);
$email      = htmlspecialchars($email);
$address    = htmlspecialchars($address);
$city       = htmlspecialchars($city);
$province   = htmlspecialchars($province);
$postalcode = htmlspecialchars($postalcode);

if ($password !== $confirm_pass) {
    header('Location: signupform.php?error=Passwords+do+not+match.');
    exit();
}

$options = ['cost' => 12];
$hashedPassword = password_hash($password, PASSWORD_DEFAULT, $options);

$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    header('Location: signupform.php?error=Email+already+registered.');
    exit();
}

$sql = "INSERT INTO users (firstname, lastname, username, email, password, address, city, province, postalcode)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssssss", $firstname, $lastname, $username, $email, $hashedPassword, $address, $city, $province, $postalcode);
$success = $stmt->execute();

if ($success) {
    session_regenerate_id(true);
    $_SESSION['user_email'] = $email;
    $_SESSION['user_name']  = $username;
    $_SESSION['user_id']    = $conn->insert_id;

    header('Location: index.php');
    exit();
} else {
    header('Location: signupform.php?error=Registration+failed.+Please+try+again.');
    exit();
}
?>

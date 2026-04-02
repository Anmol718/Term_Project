<?php
session_start();

if (!isset($_SESSION['user_email'])) {
    header('Location: loginform.php');
    exit();
}

$title = "Welcome - Algoma University";
include 'includes/header.php';
?>

<div class="page-wrapper container text-center mt-5">
    <h2 class="text-success">Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h2>
    <p class="lead">You are successfully logged in.</p>
    <a href="labsolutions.php" class="btn btn-info me-2">Lab Solutions</a>
    <a href="logout.php" class="btn btn-secondary">Logout</a>
</div>

<?php include 'includes/footer.php'; ?>

<?php
session_start();

$title = "Lab Solutions";
include 'includes/header.php';
?>

<div class="page-wrapper container">
    <?php if (isset($_SESSION['user_email'])): ?>
        <h1 class="labsolutions-title">Lab Solutions</h1>
    <?php else: ?>
        <div class="alert alert-warning alert-custom text-center mt-4">
            <strong>Access Denied.</strong> You must be logged in to view this page.
            <br><a href="loginform.php" class="btn btn-info btn-sm mt-2">Login</a>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>

<?php
session_start();
if (isset($_SESSION['user_email'])) {
    header('Location: index.php');
    exit();
}

$title = "Login – SecondHand Market";
include 'includes/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">

            <h3 class="mb-1 fw-bold">Welcome back</h3>
            <p class="text-muted mb-4">Don't have an account? <a href="signupform.php">Register here</a></p>

            <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
            <?php endif; ?>

            <form action="login.php" method="POST">
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" required autofocus>
                </div>

                <div class="mb-4">
                    <label class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" required>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg">Login</button>
                </div>
            </form>

        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

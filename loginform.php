<?php
session_start();
if (isset($_SESSION['user_email'])) {
    header('Location: labsolutions.php');
    exit();
}

$title = "Login - Algoma University";
include 'includes/header.php';
?>

<div class="page-wrapper container">
    <div class="form-card">

        <?php
        if (isset($_GET['error'])) {
            $error = htmlspecialchars($_GET['error']);
            echo '<div class="alert alert-danger">' . $error . '</div>';
        }
        ?>

        <form action="login.php" method="POST">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>

            <button type="submit" class="btn btn-primary">Login</button>
        </form>

    </div>
</div>

<?php include 'includes/footer.php'; ?>

<?php
session_start();
if (isset($_SESSION['user_email'])) {
    header('Location: index.php');
    exit();
}

$title = "Create Account – SecondHand Market";
include 'includes/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">

            <h3 class="mb-1 fw-bold">Create an Account</h3>
            <p class="text-muted mb-4">Already have one? <a href="loginform.php">Login here</a></p>

            <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
            <?php endif; ?>

            <form action="signup.php" method="POST" id="signupForm">

                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <label class="form-label">First Name</label>
                        <input type="text" class="form-control" name="firstname" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Last Name</label>
                        <input type="text" class="form-control" name="lastname" required>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <label class="form-label">Username</label>
                        <input type="text" class="form-control" name="username" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                        <div id="password-match-msg" class="form-text"></div>
                    </div>
                </div>

                <hr class="my-4">
                <p class="text-muted small mb-3">Shipping address (used at checkout)</p>

                <div class="mb-3">
                    <label class="form-label">Street Address</label>
                    <input type="text" class="form-control" name="address" placeholder="e.g. 123 Main St" required>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-5">
                        <label class="form-label">City</label>
                        <input type="text" class="form-control" name="city" required>
                    </div>
                    <div class="col-4">
                        <label class="form-label">Province</label>
                        <select class="form-select" name="province" required>
                            <option value="" disabled selected>Choose…</option>
                            <option value="AB">Alberta</option>
                            <option value="BC">British Columbia</option>
                            <option value="MB">Manitoba</option>
                            <option value="NB">New Brunswick</option>
                            <option value="NL">Newfoundland and Labrador</option>
                            <option value="NS">Nova Scotia</option>
                            <option value="ON">Ontario</option>
                            <option value="PE">Prince Edward Island</option>
                            <option value="QC">Quebec</option>
                            <option value="SK">Saskatchewan</option>
                            <option value="NT">Northwest Territories</option>
                            <option value="NU">Nunavut</option>
                            <option value="YT">Yukon</option>
                        </select>
                    </div>
                    <div class="col-3">
                        <label class="form-label">Postal Code</label>
                        <input type="text" class="form-control" name="postalcode" placeholder="A1A 1A1" required>
                    </div>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg">Create Account</button>
                </div>

            </form>
        </div>
    </div>
</div>

<script src="js/passwordcheck.js"></script>
<?php include 'includes/footer.php'; ?>

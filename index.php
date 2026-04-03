<?php
$title = "SecondHand Market – Buy & Sell Used Items";
include 'includes/header.php';

$featured = $conn->query(
    "SELECT p.*, u.username AS seller_name
     FROM products p JOIN users u ON p.seller_id = u.id
     WHERE p.stock > 0
     ORDER BY p.created_at DESC LIMIT 8"
);
?>

<!-- Hero -->
<div class="bg-dark text-white py-5">
    <div class="container py-2">
        <h1 class="mb-2">Buy &amp; Sell Second-Hand Items</h1>
        <p class="text-muted mb-4">Electronics, books, clothing, music, and more — at great prices.</p>
        <form action="products.php" method="GET" class="d-flex gap-2" style="max-width:480px">
            <input type="text" name="search" class="form-control" placeholder="Search listings…">
            <button type="submit" class="btn btn-primary px-4">Search</button>
        </form>
    </div>
</div>

<!-- Categories -->
<div class="container mt-5">
    <h5 class="mb-3">Browse by Category</h5>
    <div class="row g-2">
        <?php foreach ($CATEGORIES as $cat): ?>
        <div class="col-6 col-md-4 col-lg-2">
            <a href="products.php?category=<?= urlencode($cat) ?>"
               class="btn btn-outline-secondary w-100"><?= $cat ?></a>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Latest Listings -->
<div class="container mt-5 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Latest Listings</h5>
        <a href="products.php" class="btn btn-outline-dark btn-sm">View All</a>
    </div>

    <?php if ($featured->num_rows === 0): ?>
    <p class="text-muted">No listings yet. <a href="sell.php">Be the first to sell!</a></p>
    <?php else: ?>
    <div class="row g-3">
        <?php while ($p = $featured->fetch_assoc()): ?>
        <div class="col-sm-6 col-md-4 col-lg-3">
            <?php include 'includes/product_card.php'; ?>
        </div>
        <?php endwhile; ?>
    </div>
    <?php endif; ?>
</div>

<?php if (!isLoggedIn()): ?>
<div class="bg-light py-5 text-center border-top">
    <div class="container">
        <h5>Ready to buy or sell?</h5>
        <a href="signupform.php" class="btn btn-primary me-2">Create an Account</a>
        <a href="loginform.php" class="btn btn-outline-secondary">Login</a>
    </div>
</div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>

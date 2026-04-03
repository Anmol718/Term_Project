<?php
include 'includes/header.php';

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) { header('Location: products.php'); exit(); }

$stmt = $conn->prepare(
    "SELECT p.*, u.username AS seller_name, u.city, u.province
     FROM products p JOIN users u ON p.seller_id = u.id WHERE p.id = ?"
);
$stmt->bind_param("i", $id);
$stmt->execute();
$p = $stmt->get_result()->fetch_assoc();
if (!$p) { header('Location: products.php'); exit(); }

$title = htmlspecialchars($p['name']) . " – SecondHand Market";
?>

<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item"><a href="products.php">Browse</a></li>
            <li class="breadcrumb-item active"><?= htmlspecialchars($p['name']) ?></li>
        </ol>
    </nav>

    <div class="row g-4">
        <div class="col-md-5">
            <img src="<?= htmlspecialchars(imgSrc($p['image'])) ?>"
                 class="img-fluid rounded border"
                 alt="<?= htmlspecialchars($p['name']) ?>">
        </div>

        <div class="col-md-7">
            <span class="badge bg-secondary"><?= htmlspecialchars($p['category']) ?></span>
            <h3 class="mt-2"><?= htmlspecialchars($p['name']) ?></h3>
            <h4 class="text-success">$<?= number_format($p['price'], 2) ?></h4>

            <table class="table table-sm table-borderless w-auto mt-3">
                <tr><th class="text-muted pe-3">Condition</th><td><?= htmlspecialchars($p['condition']) ?></td></tr>
                <tr><th class="text-muted pe-3">Stock</th>
                    <td><?= $p['stock'] > 0 ? $p['stock'] . ' available' : '<span class="text-danger">Sold Out</span>' ?></td>
                </tr>
                <tr><th class="text-muted pe-3">Seller</th><td><?= htmlspecialchars($p['seller_name']) ?></td></tr>
                <tr><th class="text-muted pe-3">Location</th><td><?= htmlspecialchars($p['city'] . ', ' . $p['province']) ?></td></tr>
                <tr><th class="text-muted pe-3">Listed</th><td><?= date('M j, Y', strtotime($p['created_at'])) ?></td></tr>
            </table>

            <?php if ($p['description']): ?>
            <p><?= nl2br(htmlspecialchars($p['description'])) ?></p>
            <?php endif; ?>

            <?php if ($p['stock'] > 0): ?>
                <?php if (!isLoggedIn()): ?>
                    <a href="loginform.php" class="btn btn-primary">Login to Buy</a>
                <?php elseif ((int)$p['seller_id'] === (int)$_SESSION['user_id']): ?>
                    <div class="alert alert-info py-2">This is your listing.</div>
                <?php else: ?>
                    <form action="add_to_cart.php" method="POST" class="d-flex gap-2 align-items-center">
                        <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
                        <input type="number" name="quantity" value="1" min="1"
                               max="<?= $p['stock'] ?>" class="form-control" style="width:80px">
                        <button type="submit" class="btn btn-primary">Add to Cart</button>
                    </form>
                <?php endif; ?>
            <?php else: ?>
                <button class="btn btn-secondary" disabled>Sold Out</button>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

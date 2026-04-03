<?php
$title = "Shopping Cart – SecondHand Market";
include 'includes/header.php';
requireLogin();

// Handle messages
$msg = $_GET['msg'] ?? '';
$added = isset($_GET['added']);

// Fetch cart items
$stmt = $conn->prepare(
    "SELECT c.id AS cart_id, c.quantity, p.id AS product_id,
            p.name, p.price, p.image, p.stock, p.condition, p.category
     FROM cart c
     JOIN products p ON c.product_id = p.id
     WHERE c.user_id = ?"
);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$items = $stmt->get_result();
$rows  = $items->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$total = 0;
foreach ($rows as $row) {
    $total += $row['price'] * $row['quantity'];
}
?>

<div class="container py-5">
    <h2 class="fw-bold mb-4"><i class="bi bi-cart3 me-2"></i>Shopping Cart</h2>

    <?php if ($added): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle me-2"></i>Item added to cart!
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>
    <?php if ($msg === 'updated'): ?>
    <div class="alert alert-info alert-dismissible fade show">Cart updated.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <?php if (empty($rows)): ?>
    <div class="text-center py-5 text-muted">
        <i class="bi bi-cart-x fs-1 d-block mb-3"></i>
        <h5>Your cart is empty.</h5>
        <a href="products.php" class="btn btn-primary mt-2">Browse Listings</a>
    </div>
    <?php else: ?>
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Item</th>
                                <th>Price</th>
                                <th>Qty</th>
                                <th>Subtotal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($rows as $row):
                            $imgSrc = htmlspecialchars(imgSrc($row['image']));
                        ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <img src="<?= $imgSrc ?>" width="60" height="50"
                                         style="object-fit:cover;border-radius:6px;" alt="">
                                    <div>
                                        <a href="product.php?id=<?= $row['product_id'] ?>"
                                           class="fw-semibold text-dark text-decoration-none">
                                            <?= htmlspecialchars($row['name']) ?>
                                        </a>
                                        <div class="small text-muted"><?= htmlspecialchars($row['condition']) ?></div>
                                    </div>
                                </div>
                            </td>
                            <td>$<?= number_format($row['price'], 2) ?></td>
                            <td style="width:120px">
                                <form action="update_cart.php" method="POST" class="d-flex gap-1 align-items-center">
                                    <input type="hidden" name="cart_id" value="<?= $row['cart_id'] ?>">
                                    <input type="number" name="quantity" value="<?= $row['quantity'] ?>"
                                           min="1" max="<?= $row['stock'] ?>"
                                           class="form-control form-control-sm" style="width:65px">
                                    <button type="submit" class="btn btn-outline-secondary btn-sm">
                                        <i class="bi bi-arrow-clockwise"></i>
                                    </button>
                                </form>
                            </td>
                            <td class="fw-semibold">$<?= number_format($row['price'] * $row['quantity'], 2) ?></td>
                            <td>
                                <form action="remove_from_cart.php" method="POST">
                                    <input type="hidden" name="cart_id" value="<?= $row['cart_id'] ?>">
                                    <button type="submit" class="btn btn-outline-danger btn-sm"
                                            title="Remove">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Order Summary</h5>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Subtotal</span>
                        <span>$<?= number_format($total, 2) ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Shipping</span>
                        <span class="text-success">Free</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between fw-bold fs-5 mb-4">
                        <span>Total</span>
                        <span class="text-success">$<?= number_format($total, 2) ?></span>
                    </div>
                    <a href="checkout.php" class="btn btn-primary w-100 btn-lg">
                        <i class="bi bi-credit-card me-2"></i>Proceed to Checkout
                    </a>
                    <a href="products.php" class="btn btn-outline-secondary w-100 mt-2">
                        Continue Shopping
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>

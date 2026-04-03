<?php
$title = "Checkout - SecondHand Market";
include 'includes/header.php';
requireLogin();

// Fetch cart items
$stmt = $conn->prepare(
    "SELECT c.quantity, p.id AS product_id, p.name, p.price, p.image, p.stock, p.condition
     FROM cart c
     JOIN products p ON c.product_id = p.id
     WHERE c.user_id = ?"
);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

if (empty($items)) {
    header('Location: cart.php');
    exit();
}

$total = 0;
foreach ($items as $item) {
    $total += $item['price'] * $item['quantity'];
}

// Fetch user address
$stmt = $conn->prepare("SELECT firstname, lastname, address, city, province, postalcode FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();
?>

<div class="container py-5">
    <h2 class="fw-bold mb-4"><i class="bi bi-credit-card me-2"></i>Checkout</h2>

    <div class="row g-4">
        <!-- Order Summary -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white fw-semibold">Order Summary</div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        <?php foreach ($items as $item):
                            $imgSrc = htmlspecialchars(imgSrc($item['image']));
                        ?>
                            <li class="list-group-item d-flex align-items-center gap-3 py-3">
                                <img src="<?= $imgSrc ?>" width="55" height="45"
                                    style="object-fit:cover;border-radius:6px;" alt="">
                                <div class="flex-grow-1">
                                    <div class="fw-semibold"><?= htmlspecialchars($item['name']) ?></div>
                                    <div class="small text-muted">Qty: <?= $item['quantity'] ?> &bull; <?= htmlspecialchars($item['condition']) ?></div>
                                </div>
                                <span class="fw-bold">$<?= number_format($item['price'] * $item['quantity'], 2) ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="card-footer bg-white">
                    <div class="d-flex justify-content-between fw-bold">
                        <span>Total</span>
                        <span class="text-success fs-5">$<?= number_format($total, 2) ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Shipping & Place Order -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-semibold">Shipping Address</div>
                <div class="card-body">
                    <p class="mb-3 text-muted small">Delivering to your registered address:</p>
                    <address class="mb-4">
                        <strong><?= htmlspecialchars($user['firstname'] . ' ' . $user['lastname']) ?></strong><br>
                        <?= htmlspecialchars($user['address']) ?><br>
                        <?= htmlspecialchars($user['city'] . ', ' . $user['province'] . ' ' . $user['postalcode']) ?>
                    </address>

                    <hr>
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">Subtotal</span><span>$<?= number_format($total, 2) ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Shipping</span><span class="text-success">Free</span>
                    </div>
                    <div class="d-flex justify-content-between fw-bold fs-5 mb-4">
                        <span>Total</span><span class="text-success">$<?= number_format($total, 2) ?></span>
                    </div>

                    <form action="place_order.php" method="POST">
                        <input type="hidden" name="total" value="<?= $total ?>">
                        <button type="submit" class="btn btn-success w-100 btn-lg"
                            onclick="return confirm('Confirm your order for $<?= number_format($total, 2) ?>?')">
                            <i class="bi bi-check-circle me-2"></i>Place Order
                        </button>
                    </form>
                    <a href="cart.php" class="btn btn-outline-secondary w-100 mt-2">Back to Cart</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
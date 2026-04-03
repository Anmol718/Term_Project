<?php
$title = "My Orders – SecondHand Market";
include 'includes/header.php';
requireLogin();

$newOrderId = isset($_GET['new']) ? (int)$_GET['new'] : 0;

$stmt = $conn->prepare(
    "SELECT o.id, o.total_price, o.order_date, o.status, o.shipping_address,
            COUNT(oi.id) AS item_count
     FROM orders o
     LEFT JOIN order_items oi ON o.id = oi.order_id
     WHERE o.user_id = ?
     GROUP BY o.id
     ORDER BY o.order_date DESC"
);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$orders = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$statusColors = [
    'Pending'   => 'warning',
    'Shipped'   => 'info',
    'Delivered' => 'success',
    'Cancelled' => 'danger',
];
?>

<div class="container py-5">
    <h2 class="fw-bold mb-4"><i class="bi bi-bag me-2"></i>My Orders</h2>

    <?php if ($newOrderId): ?>
    <div class="alert alert-success">
        <i class="bi bi-check-circle me-2"></i>
        Order #<?= $newOrderId ?> placed successfully! Thank you for your purchase.
    </div>
    <?php endif; ?>

    <?php if (empty($orders)): ?>
    <div class="text-center py-5 text-muted">
        <i class="bi bi-bag-x fs-1 d-block mb-3"></i>
        <h5>No orders yet.</h5>
        <a href="products.php" class="btn btn-primary mt-2">Start Shopping</a>
    </div>
    <?php else: ?>
    <div class="row g-3">
        <?php foreach ($orders as $order): ?>
        <div class="col-12">
            <div class="card border-0 shadow-sm <?= $order['id'] === $newOrderId ? 'border border-success' : '' ?>">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-2">
                            <div class="text-muted small">Order #</div>
                            <div class="fw-bold"><?= $order['id'] ?></div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-muted small">Date</div>
                            <div><?= date('M j, Y', strtotime($order['order_date'])) ?></div>
                        </div>
                        <div class="col-md-2">
                            <div class="text-muted small">Items</div>
                            <div><?= $order['item_count'] ?></div>
                        </div>
                        <div class="col-md-2">
                            <div class="text-muted small">Total</div>
                            <div class="fw-bold text-success">$<?= number_format($order['total_price'], 2) ?></div>
                        </div>
                        <div class="col-md-2">
                            <span class="badge bg-<?= $statusColors[$order['status']] ?? 'secondary' ?>">
                                <?= htmlspecialchars($order['status']) ?>
                            </span>
                        </div>
                        <div class="col-md-1 text-end">
                            <a href="order_detail.php?id=<?= $order['id'] ?>"
                               class="btn btn-outline-secondary btn-sm">
                                <i class="bi bi-eye"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>

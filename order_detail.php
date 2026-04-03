<?php
$title = "Order Details – SecondHand Market";
include 'includes/header.php';
requireLogin();

$order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch order (must belong to this user, or admin can see any)
if (isAdmin()) {
    $stmt = $conn->prepare("SELECT o.*, u.username FROM orders o JOIN users u ON o.user_id = u.id WHERE o.id = ?");
    $stmt->bind_param("i", $order_id);
} else {
    $stmt = $conn->prepare("SELECT o.*, u.username FROM orders o JOIN users u ON o.user_id = u.id WHERE o.id = ? AND o.user_id = ?");
    $stmt->bind_param("ii", $order_id, $_SESSION['user_id']);
}
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$order) {
    header('Location: orders.php');
    exit();
}

// Fetch order items
$stmt = $conn->prepare(
    "SELECT oi.quantity, oi.price, p.id AS product_id, p.name, p.image, p.category, p.condition
     FROM order_items oi
     JOIN products p ON oi.product_id = p.id
     WHERE oi.order_id = ?"
);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$statusColors = [
    'Pending'   => 'warning',
    'Shipped'   => 'info',
    'Delivered' => 'success',
    'Cancelled' => 'danger',
];
?>

<div class="container py-5">
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="<?= isAdmin() ? 'admin/orders.php' : 'orders.php' ?>" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h2 class="fw-bold mb-0">Order #<?= $order['id'] ?></h2>
        <span class="badge bg-<?= $statusColors[$order['status']] ?? 'secondary' ?> fs-6">
            <?= htmlspecialchars($order['status']) ?>
        </span>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-semibold">Items Ordered</div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        <?php foreach ($items as $item):
                            $imgSrc = htmlspecialchars(imgSrc($item['image']));
                        ?>
                        <li class="list-group-item d-flex align-items-center gap-3 py-3">
                            <img src="<?= $imgSrc ?>" width="60" height="50"
                                 style="object-fit:cover;border-radius:6px;" alt="">
                            <div class="flex-grow-1">
                                <a href="product.php?id=<?= $item['product_id'] ?>"
                                   class="fw-semibold text-dark text-decoration-none">
                                    <?= htmlspecialchars($item['name']) ?>
                                </a>
                                <div class="small text-muted">
                                    <?= htmlspecialchars($item['category']) ?> &bull;
                                    <?= htmlspecialchars($item['condition']) ?>
                                </div>
                            </div>
                            <div class="text-end">
                                <div class="text-muted small">Qty: <?= $item['quantity'] ?></div>
                                <div class="fw-bold">$<?= number_format($item['price'] * $item['quantity'], 2) ?></div>
                            </div>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white fw-semibold">Order Info</div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-5 text-muted">Date</dt>
                        <dd class="col-7"><?= date('M j, Y g:ia', strtotime($order['order_date'])) ?></dd>
                        <?php if (isAdmin()): ?>
                        <dt class="col-5 text-muted">Buyer</dt>
                        <dd class="col-7"><?= htmlspecialchars($order['username']) ?></dd>
                        <?php endif; ?>
                        <dt class="col-5 text-muted">Ship To</dt>
                        <dd class="col-7"><?= htmlspecialchars($order['shipping_address']) ?></dd>
                        <dt class="col-5 text-muted">Total</dt>
                        <dd class="col-7 fw-bold text-success">$<?= number_format($order['total_price'], 2) ?></dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

<?php
$title = "Manage Orders – Admin";
include '../includes/header.php';
requireAdmin();

$msg    = $_GET['msg'] ?? '';
$filter = trim($_GET['status'] ?? '');

$sql = "SELECT o.id, o.total_price, o.order_date, o.status, u.username, u.email,
               COUNT(oi.id) AS item_count
        FROM orders o
        JOIN users u ON o.user_id = u.id
        LEFT JOIN order_items oi ON o.id = oi.order_id"
    . ($filter ? " WHERE o.status = '" . $conn->real_escape_string($filter) . "'" : "")
    . " GROUP BY o.id ORDER BY o.order_date DESC";

$orders = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);

$statusColors = ['Pending'=>'warning','Shipped'=>'info','Delivered'=>'success','Cancelled'=>'danger'];
$allStatuses  = ['Pending','Shipped','Delivered','Cancelled'];
?>

<div class="container py-5">
    <h2 class="fw-bold mb-4"><i class="bi bi-bag me-2"></i>Manage Orders</h2>

    <?php if ($msg === 'updated'): ?>
    <div class="alert alert-success">Order status updated.</div>
    <?php endif; ?>

    <!-- Status filter tabs -->
    <div class="mb-4 d-flex gap-2 flex-wrap">
        <a href="orders.php" class="btn btn-sm <?= !$filter ? 'btn-dark' : 'btn-outline-secondary' ?>">All</a>
        <?php foreach ($allStatuses as $s): ?>
        <a href="orders.php?status=<?= $s ?>"
           class="btn btn-sm <?= $filter === $s ? 'btn-' . $statusColors[$s] : 'btn-outline-' . $statusColors[$s] ?>">
            <?= $s ?>
        </a>
        <?php endforeach; ?>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th><th>Buyer</th><th>Items</th><th>Total</th>
                        <th>Date</th><th>Status</th><th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($orders)): ?>
                <tr><td colspan="7" class="text-center text-muted py-4">No orders found.</td></tr>
                <?php endif; ?>
                <?php foreach ($orders as $o): ?>
                <tr>
                    <td><a href="../order_detail.php?id=<?= $o['id'] ?>">#<?= $o['id'] ?></a></td>
                    <td>
                        <div class="fw-semibold small"><?= htmlspecialchars($o['username']) ?></div>
                        <div class="text-muted small"><?= htmlspecialchars($o['email']) ?></div>
                    </td>
                    <td><?= $o['item_count'] ?></td>
                    <td class="fw-bold text-success">$<?= number_format($o['total_price'], 2) ?></td>
                    <td class="text-muted small"><?= date('M j, Y', strtotime($o['order_date'])) ?></td>
                    <td>
                        <span class="badge bg-<?= $statusColors[$o['status']] ?? 'secondary' ?>">
                            <?= htmlspecialchars($o['status']) ?>
                        </span>
                    </td>
                    <td>
                        <form action="update_order_status.php" method="POST" class="d-flex gap-1">
                            <input type="hidden" name="order_id" value="<?= $o['id'] ?>">
                            <select name="status" class="form-select form-select-sm" style="width:120px">
                                <?php foreach ($allStatuses as $s): ?>
                                <option value="<?= $s ?>" <?= $o['status'] === $s ? 'selected' : '' ?>><?= $s ?></option>
                                <?php endforeach; ?>
                            </select>
                            <button type="submit" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-check-lg"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white text-muted small"><?= count($orders) ?> order(s).</div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>

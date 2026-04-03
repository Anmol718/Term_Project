<?php
$title = "Admin Dashboard – SecondHand Market";
include '../includes/header.php';
requireAdmin();

// Stats
$stats = [];
foreach ([
    'users'    => "SELECT COUNT(*) FROM users WHERE is_admin = 0",
    'products' => "SELECT COUNT(*) FROM products",
    'orders'   => "SELECT COUNT(*) FROM orders",
    'revenue'  => "SELECT COALESCE(SUM(total_price),0) FROM orders WHERE status != 'Cancelled'",
] as $key => $sql) {
    $res = $conn->query($sql);
    $stats[$key] = $res->fetch_row()[0];
}

// Recent orders
$recentOrders = $conn->query(
    "SELECT o.id, o.total_price, o.order_date, o.status, u.username
     FROM orders o
     JOIN users u ON o.user_id = u.id
     ORDER BY o.order_date DESC
     LIMIT 5"
)->fetch_all(MYSQLI_ASSOC);

// Low stock alerts
$lowStock = $conn->query(
    "SELECT id, name, stock FROM products WHERE stock <= 1 ORDER BY stock ASC LIMIT 5"
)->fetch_all(MYSQLI_ASSOC);

$statusColors = ['Pending'=>'warning','Shipped'=>'info','Delivered'=>'success','Cancelled'=>'danger'];
?>

<div class="container py-5">
    <h2 class="fw-bold mb-1"><i class="bi bi-shield-lock me-2"></i>Admin Dashboard</h2>
    <p class="text-muted mb-4">Marketplace overview</p>

    <!-- Stat cards -->
    <div class="row g-4 mb-5">
        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm text-center py-4">
                <div class="card-body">
                    <i class="bi bi-people fs-2 text-primary mb-2 d-block"></i>
                    <h3 class="fw-bold mb-0"><?= number_format($stats['users']) ?></h3>
                    <p class="text-muted mb-0">Registered Users</p>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm text-center py-4">
                <div class="card-body">
                    <i class="bi bi-tags fs-2 text-success mb-2 d-block"></i>
                    <h3 class="fw-bold mb-0"><?= number_format($stats['products']) ?></h3>
                    <p class="text-muted mb-0">Active Listings</p>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm text-center py-4">
                <div class="card-body">
                    <i class="bi bi-bag-check fs-2 text-info mb-2 d-block"></i>
                    <h3 class="fw-bold mb-0"><?= number_format($stats['orders']) ?></h3>
                    <p class="text-muted mb-0">Total Orders</p>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm text-center py-4">
                <div class="card-body">
                    <i class="bi bi-currency-dollar fs-2 text-warning mb-2 d-block"></i>
                    <h3 class="fw-bold mb-0">$<?= number_format($stats['revenue'], 2) ?></h3>
                    <p class="text-muted mb-0">Total Revenue</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Recent Orders -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <span class="fw-semibold">Recent Orders</span>
                    <a href="orders.php" class="btn btn-outline-secondary btn-sm">View All</a>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr><th>#</th><th>User</th><th>Total</th><th>Date</th><th>Status</th></tr>
                        </thead>
                        <tbody>
                        <?php foreach ($recentOrders as $o): ?>
                        <tr>
                            <td><a href="../order_detail.php?id=<?= $o['id'] ?>">#<?= $o['id'] ?></a></td>
                            <td><?= htmlspecialchars($o['username']) ?></td>
                            <td class="fw-semibold">$<?= number_format($o['total_price'], 2) ?></td>
                            <td class="text-muted small"><?= date('M j, Y', strtotime($o['order_date'])) ?></td>
                            <td><span class="badge bg-<?= $statusColors[$o['status']] ?? 'secondary' ?>"><?= $o['status'] ?></span></td>
                        </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Quick Nav + Low Stock -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white fw-semibold">Quick Actions</div>
                <div class="list-group list-group-flush">
                    <a href="products.php" class="list-group-item list-group-item-action">
                        <i class="bi bi-tags me-2 text-success"></i>Manage Products
                    </a>
                    <a href="orders.php" class="list-group-item list-group-item-action">
                        <i class="bi bi-bag me-2 text-info"></i>Manage Orders
                    </a>
                    <a href="users.php" class="list-group-item list-group-item-action">
                        <i class="bi bi-people me-2 text-primary"></i>Manage Users
                    </a>
                    <a href="../sell.php" class="list-group-item list-group-item-action">
                        <i class="bi bi-plus-circle me-2 text-warning"></i>Add New Listing
                    </a>
                </div>
            </div>

            <?php if (!empty($lowStock)): ?>
            <div class="card border-0 shadow-sm border-warning">
                <div class="card-header bg-warning bg-opacity-10 fw-semibold text-warning">
                    <i class="bi bi-exclamation-triangle me-1"></i>Low / Sold Out Stock
                </div>
                <ul class="list-group list-group-flush">
                <?php foreach ($lowStock as $item): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="../edit_listing.php?id=<?= $item['id'] ?>"
                           class="text-dark text-decoration-none small">
                            <?= htmlspecialchars($item['name']) ?>
                        </a>
                        <span class="badge bg-<?= $item['stock'] == 0 ? 'danger' : 'warning' ?> rounded-pill">
                            <?= $item['stock'] === '0' ? 'Sold Out' : $item['stock'] . ' left' ?>
                        </span>
                    </li>
                <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>

<?php
$title = "Manage Products – Admin";
include '../includes/header.php';
requireAdmin();

$msg = $_GET['msg'] ?? '';
$search   = trim($_GET['search'] ?? '');
$category = trim($_GET['category'] ?? '');

$where  = [];
$params = [];
$types  = '';

if ($search) {
    $where[]  = "p.name LIKE ?";
    $params[] = "%$search%";
    $types   .= 's';
}
if ($category) {
    $where[]  = "p.category = ?";
    $params[] = $category;
    $types   .= 's';
}

$sql = "SELECT p.*, u.username AS seller_name
        FROM products p
        JOIN users u ON p.seller_id = u.id"
    . ($where ? ' WHERE ' . implode(' AND ', $where) : '')
    . " ORDER BY p.created_at DESC";

$stmt = $conn->prepare($sql);
if ($params) $stmt->bind_param($types, ...$params);
$stmt->execute();
$products = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0"><i class="bi bi-tags me-2"></i>Manage Products</h2>
        <a href="../sell.php" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i>Add Listing
        </a>
    </div>

    <?php if ($msg === 'deleted'): ?>
    <div class="alert alert-success">Product deleted.</div>
    <?php elseif ($msg === 'updated'): ?>
    <div class="alert alert-success">Product updated.</div>
    <?php endif; ?>

    <!-- Filter bar -->
    <form action="products.php" method="GET" class="row g-2 mb-4">
        <div class="col-md-5">
            <input type="text" name="search" class="form-control" placeholder="Search by name…"
                   value="<?= htmlspecialchars($search) ?>">
        </div>
        <div class="col-md-3">
            <select name="category" class="form-select">
                <option value="">All Categories</option>
                <?php foreach ($CATEGORIES as $c): ?>
                <option value="<?= $c ?>" <?= $category === $c ? 'selected' : '' ?>><?= $c ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-outline-primary w-100">Filter</button>
        </div>
        <div class="col-md-2">
            <a href="products.php" class="btn btn-outline-secondary w-100">Clear</a>
        </div>
    </form>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th><th>Item</th><th>Category</th><th>Condition</th>
                        <th>Price</th><th>Stock</th><th>Seller</th><th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($products)): ?>
                <tr><td colspan="8" class="text-center text-muted py-4">No products found.</td></tr>
                <?php endif; ?>
                <?php foreach ($products as $p):
                    $imgSrc = htmlspecialchars(imgSrc($p['image']));
                ?>
                <tr>
                    <td class="text-muted small"><?= $p['id'] ?></td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <img src="<?= $imgSrc ?>" width="45" height="38"
                                 style="object-fit:cover;border-radius:4px;" alt="">
                            <a href="../product.php?id=<?= $p['id'] ?>"
                               class="text-dark text-decoration-none fw-semibold small">
                                <?= htmlspecialchars($p['name']) ?>
                            </a>
                        </div>
                    </td>
                    <td><span class="badge bg-secondary"><?= htmlspecialchars($p['category']) ?></span></td>
                    <td class="small"><?= htmlspecialchars($p['condition']) ?></td>
                    <td class="fw-semibold text-success">$<?= number_format($p['price'], 2) ?></td>
                    <td>
                        <span class="badge bg-<?= $p['stock'] == 0 ? 'danger' : ($p['stock'] <= 2 ? 'warning' : 'success') ?>">
                            <?= $p['stock'] ?>
                        </span>
                    </td>
                    <td class="small"><?= htmlspecialchars($p['seller_name']) ?></td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="../edit_listing.php?id=<?= $p['id'] ?>"
                               class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="../delete_listing.php" method="POST"
                                  onsubmit="return confirm('Delete this product?')">
                                <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white text-muted small">
            <?= count($products) ?> product(s) found.
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>

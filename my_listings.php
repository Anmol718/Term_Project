<?php
$title = "My Listings – SecondHand Market";
include 'includes/header.php';
requireLogin();

$msg = $_GET['msg'] ?? '';

$stmt = $conn->prepare(
    "SELECT * FROM products WHERE seller_id = ? ORDER BY created_at DESC"
);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$listings = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0"><i class="bi bi-tag me-2"></i>My Listings</h2>
        <a href="sell.php" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i>List New Item
        </a>
    </div>

    <?php if ($msg === 'deleted'): ?>
    <div class="alert alert-success">Listing deleted successfully.</div>
    <?php endif; ?>

    <?php if (empty($listings)): ?>
    <div class="text-center py-5 text-muted">
        <i class="bi bi-inbox fs-1 d-block mb-3"></i>
        <h5>You haven't listed anything yet.</h5>
        <a href="sell.php" class="btn btn-primary mt-2">List Your First Item</a>
    </div>
    <?php else: ?>
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Item</th>
                        <th>Category</th>
                        <th>Condition</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Listed</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($listings as $item):
                    $imgSrc = htmlspecialchars(imgSrc($item['image']));
                ?>
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <img src="<?= $imgSrc ?>" width="50" height="42"
                                 style="object-fit:cover;border-radius:6px;" alt="">
                            <a href="product.php?id=<?= $item['id'] ?>"
                               class="fw-semibold text-dark text-decoration-none">
                                <?= htmlspecialchars($item['name']) ?>
                            </a>
                        </div>
                    </td>
                    <td><span class="badge bg-secondary"><?= htmlspecialchars($item['category']) ?></span></td>
                    <td><?= htmlspecialchars($item['condition']) ?></td>
                    <td class="fw-semibold text-success">$<?= number_format($item['price'], 2) ?></td>
                    <td>
                        <?php if ($item['stock'] > 0): ?>
                            <span class="text-success"><?= $item['stock'] ?></span>
                        <?php else: ?>
                            <span class="text-danger">Sold Out</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-muted small"><?= date('M j, Y', strtotime($item['created_at'])) ?></td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="edit_listing.php?id=<?= $item['id'] ?>"
                               class="btn btn-outline-primary btn-sm" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="delete_listing.php" method="POST"
                                  onsubmit="return confirm('Delete this listing?')">
                                <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
                                <button type="submit" class="btn btn-outline-danger btn-sm" title="Delete">
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
    </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>

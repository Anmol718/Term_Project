<?php
$title = "Edit Listing – SecondHand Market";
include 'includes/header.php';
requireLogin();

$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$error      = isset($_GET['error']) ? htmlspecialchars($_GET['error']) : '';

// Fetch product — must belong to the current user (unless admin)
if (isAdmin()) {
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
} else {
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ? AND seller_id = ?");
    $stmt->bind_param("ii", $product_id, $_SESSION['user_id']);
}
$stmt->execute();
$p = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$p) {
    header('Location: my_listings.php');
    exit();
}

$imgSrc = htmlspecialchars(imgSrc($p['image']));
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="d-flex align-items-center gap-3 mb-4">
                <a href="<?= isAdmin() ? 'admin/products.php' : 'my_listings.php' ?>"
                   class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <h2 class="fw-bold mb-0">Edit Listing</h2>
            </div>

            <?php if ($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form action="update_listing.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="product_id" value="<?= $p['id'] ?>">

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Item Title <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" required
                                   value="<?= htmlspecialchars($p['name']) ?>">
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Category <span class="text-danger">*</span></label>
                                <select name="category" class="form-select" required>
                                    <?php foreach ($CATEGORIES as $c): ?>
                                    <option value="<?= $c ?>" <?= $p['category'] === $c ? 'selected' : '' ?>><?= $c ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Condition <span class="text-danger">*</span></label>
                                <select name="condition" class="form-select" required>
                                    <?php foreach ($CONDITIONS as $c): ?>
                                    <option value="<?= $c ?>" <?= $p['condition'] === $c ? 'selected' : '' ?>><?= $c ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Price (CAD) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" name="price" class="form-control" required
                                           min="0.01" step="0.01" value="<?= $p['price'] ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Stock</label>
                                <input type="number" name="stock" class="form-control"
                                       min="0" value="<?= $p['stock'] ?>">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea name="description" class="form-control" rows="4"><?= htmlspecialchars($p['description']) ?></textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Product Image</label>
                            <?php if (!empty($p['image'])): ?>
                            <div class="mb-2">
                                <img src="<?= $imgSrc ?>" height="80" class="rounded border" alt="Current image">
                                <div class="form-text">Current image. Upload a new one to replace it.</div>
                            </div>
                            <?php endif; ?>
                            <input type="file" name="image" class="form-control" accept="image/*">
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            <i class="bi bi-save me-2"></i>Save Changes
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

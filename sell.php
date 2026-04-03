<?php
$title = "Sell an Item – SecondHand Market";
include 'includes/header.php';
requireLogin();

$error = isset($_GET['error']) ? htmlspecialchars($_GET['error']) : '';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <h2 class="fw-bold mb-1"><i class="bi bi-plus-circle me-2"></i>List an Item for Sale</h2>
            <p class="text-muted mb-4">Fill in the details below and your item will go live immediately.</p>

            <?php if ($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form action="save_listing.php" method="POST" enctype="multipart/form-data"
                          id="sellForm" novalidate>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Item Title <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" required
                                   placeholder="e.g. iPhone 12 64GB Space Gray">
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Category <span class="text-danger">*</span></label>
                                <select name="category" class="form-select" required>
                                    <option value="" disabled selected>Choose…</option>
                                    <?php foreach ($CATEGORIES as $c): ?>
                                    <option value="<?= $c ?>"><?= $c ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Condition <span class="text-danger">*</span></label>
                                <select name="condition" class="form-select" required>
                                    <option value="" disabled selected>Choose…</option>
                                    <?php foreach ($CONDITIONS as $c): ?>
                                    <option value="<?= $c ?>"><?= $c ?></option>
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
                                           min="0.01" step="0.01" placeholder="0.00">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Quantity</label>
                                <input type="number" name="stock" class="form-control"
                                       min="1" value="1">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea name="description" class="form-control" rows="4"
                                      placeholder="Describe the item — include any defects, included accessories, etc."></textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Product Image</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                            <div class="form-text">JPG, PNG or GIF – max 5 MB. Leave blank to use a placeholder.</div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            <i class="bi bi-upload me-2"></i>Publish Listing
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Client-side validation
document.getElementById('sellForm').addEventListener('submit', function(e) {
    const name  = document.querySelector('[name="name"]').value.trim();
    const price = parseFloat(document.querySelector('[name="price"]').value);
    const cat   = document.querySelector('[name="category"]').value;
    const cond  = document.querySelector('[name="condition"]').value;

    if (!name || !cat || !cond || isNaN(price) || price <= 0) {
        e.preventDefault();
        alert('Please fill in all required fields with valid values.');
    }
});
</script>

<?php include 'includes/footer.php'; ?>

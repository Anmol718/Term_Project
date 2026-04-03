<?php
$title = "Browse – SecondHand Market";
include 'includes/header.php';

$search   = trim($_GET['search']   ?? '');
$category = trim($_GET['category'] ?? '');
$cond     = trim($_GET['condition'] ?? '');
$sort     = trim($_GET['sort']     ?? 'newest');

$where  = ["p.stock > 0"];
$params = [];
$types  = '';

if ($search !== '') {
    $where[]  = "(p.name LIKE ? OR p.description LIKE ?)";
    $like     = "%$search%";
    $params[] = $like;
    $params[] = $like;
    $types   .= 'ss';
}
if ($category !== '') {
    $where[]  = "p.category = ?";
    $params[] = $category;
    $types   .= 's';
}
if ($cond !== '') {
    $where[]  = "p.condition = ?";
    $params[] = $cond;
    $types   .= 's';
}

$orderBy = match($sort) {
    'price_asc'  => 'p.price ASC',
    'price_desc' => 'p.price DESC',
    'oldest'     => 'p.created_at ASC',
    default      => 'p.created_at DESC',
};

$sql  = "SELECT p.*, u.username AS seller_name FROM products p JOIN users u ON p.seller_id = u.id"
      . " WHERE " . implode(' AND ', $where)
      . " ORDER BY $orderBy";

$stmt = $conn->prepare($sql);
if ($params) $stmt->bind_param($types, ...$params);
$stmt->execute();
$products = $stmt->get_result();
?>

<div class="container py-4">

    <form action="products.php" method="GET" class="row g-2 mb-4">
        <div class="col-sm-4">
            <input type="text" name="search" class="form-control" placeholder="Search…"
                   value="<?= htmlspecialchars($search) ?>">
        </div>
        <div class="col-sm-2">
            <select name="category" class="form-select">
                <option value="">All Categories</option>
                <?php foreach ($CATEGORIES as $c): ?>
                <option value="<?= $c ?>" <?= $category === $c ? 'selected' : '' ?>><?= $c ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-sm-2">
            <select name="condition" class="form-select">
                <option value="">Any Condition</option>
                <?php foreach ($CONDITIONS as $c): ?>
                <option value="<?= $c ?>" <?= $cond === $c ? 'selected' : '' ?>><?= $c ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-sm-2">
            <select name="sort" class="form-select">
                <option value="newest"    <?= $sort==='newest'    ? 'selected':'' ?>>Newest</option>
                <option value="price_asc" <?= $sort==='price_asc' ? 'selected':'' ?>>Price: Low–High</option>
                <option value="price_desc"<?= $sort==='price_desc'? 'selected':'' ?>>Price: High–Low</option>
            </select>
        </div>
        <div class="col-sm-1">
            <button type="submit" class="btn btn-primary w-100">Go</button>
        </div>
        <div class="col-sm-1">
            <a href="products.php" class="btn btn-outline-secondary w-100">Clear</a>
        </div>
    </form>

    <p class="text-muted small mb-3"><?= $products->num_rows ?> listing(s) found</p>

    <?php if ($products->num_rows === 0): ?>
    <p>No results. <a href="products.php">Clear filters</a></p>
    <?php else: ?>
    <div class="row g-3">
        <?php while ($p = $products->fetch_assoc()): ?>
        <div class="col-sm-6 col-md-4 col-lg-3">
            <?php include 'includes/product_card.php'; ?>
        </div>
        <?php endwhile; ?>
    </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>

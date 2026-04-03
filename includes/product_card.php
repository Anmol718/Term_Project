<?php // expects $p array with product fields ?>
<div class="card h-100">
    <a href="<?= BASE_URL ?>/product.php?id=<?= $p['id'] ?>">
        <img src="<?= htmlspecialchars(imgSrc($p['image'])) ?>"
             class="card-img-top"
             style="height:180px;object-fit:cover;"
             alt="<?= htmlspecialchars($p['name']) ?>">
    </a>
    <div class="card-body d-flex flex-column">
        <span class="badge bg-secondary mb-1" style="width:fit-content"><?= htmlspecialchars($p['category']) ?></span>
        <p class="card-text fw-semibold mb-1">
            <a href="<?= BASE_URL ?>/product.php?id=<?= $p['id'] ?>" class="text-dark text-decoration-none">
                <?= htmlspecialchars($p['name']) ?>
            </a>
        </p>
        <p class="text-muted small mb-2"><?= htmlspecialchars($p['condition']) ?></p>
        <p class="fw-bold text-success mb-0 mt-auto">$<?= number_format($p['price'], 2) ?></p>
    </div>
</div>

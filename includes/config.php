<?php
define('BASE_URL', '/TermProject');
define('UPLOADS_DIR', __DIR__ . '/../uploads/products/');
define('UPLOADS_URL', BASE_URL . '/uploads/products/');
define('PLACEHOLDER_IMG', 'https://placehold.co/400x300/dee2e6/6c757d?text=No+Image');

$CATEGORIES = ['Electronics', 'Books', 'Clothing', 'Music', 'Collectibles', 'Other'];
$CONDITIONS = ['Like New', 'Good', 'Fair', 'Poor'];

function imgSrc(?string $image): string {
    if (empty($image)) return PLACEHOLDER_IMG;
    if (str_starts_with($image, 'http')) return $image;
    if (file_exists(UPLOADS_DIR . $image)) return UPLOADS_URL . $image;
    return PLACEHOLDER_IMG;
}

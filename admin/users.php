<?php
$title = "Manage Users – Admin";
include '../includes/header.php';
requireAdmin();

$users = $conn->query(
    "SELECT id, firstname, lastname, username, email, city, province, is_admin, created_at
     FROM users
     ORDER BY created_at DESC"
)->fetch_all(MYSQLI_ASSOC);
?>

<div class="container py-5">
    <h2 class="fw-bold mb-4"><i class="bi bi-people me-2"></i>Manage Users</h2>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th><th>Name</th><th>Username</th><th>Email</th>
                        <th>Location</th><th>Role</th><th>Joined</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($users as $u): ?>
                <tr>
                    <td class="text-muted small"><?= $u['id'] ?></td>
                    <td><?= htmlspecialchars($u['firstname'] . ' ' . $u['lastname']) ?></td>
                    <td><?= htmlspecialchars($u['username']) ?></td>
                    <td class="text-muted small"><?= htmlspecialchars($u['email']) ?></td>
                    <td class="small"><?= htmlspecialchars($u['city'] . ', ' . $u['province']) ?></td>
                    <td>
                        <?php if ($u['is_admin']): ?>
                            <span class="badge bg-danger">Admin</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">User</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-muted small"><?= date('M j, Y', strtotime($u['created_at'])) ?></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white text-muted small"><?= count($users) ?> user(s) registered.</div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>

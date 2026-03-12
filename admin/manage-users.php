<?php require 'admin-header.php'; ?>

<div class="dashboard-header">
    <h1>Manage Users</h1>
</div>

<?php
// Handle delete user
if(isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    // Prevent admin from deleting themselves
    if($delete_id != $_SESSION['user_id']) {
        // Optionally delete related records (rentals, reviews, notifications) via cascade or manually
        $pdo->prepare("DELETE FROM users WHERE id = ?")->execute([$delete_id]);
        $_SESSION['message'] = "User deleted.";
    } else {
        $_SESSION['error'] = "You cannot delete your own account.";
    }
    header("Location: manage-users.php");
    exit;
}

$users = $pdo->query("SELECT * FROM users WHERE user_type = 'customer' ORDER BY created_at DESC")->fetchAll();
?>

<?php if(isset($_SESSION['message'])): ?>
    <div class="success"><?= $_SESSION['message']; unset($_SESSION['message']); ?></div>
<?php endif; ?>
<?php if(isset($_SESSION['error'])): ?>
    <div class="error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
<?php endif; ?>

<table class="admin-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Registered On</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if(count($users) > 0): ?>
            <?php foreach($users as $u): ?>
            <tr>
                <td><?= $u['id'] ?></td>
                <td><?= htmlspecialchars($u['full_name']) ?></td>
                <td><?= htmlspecialchars($u['email']) ?></td>
                <td><?= htmlspecialchars($u['phone']) ?></td>
                <td><?= date('d M Y', strtotime($u['created_at'])) ?></td>
                <td>
                    <a href="?delete=<?= $u['id'] ?>" class="btn btn-outline btn-sm delete" onclick="return confirm('Delete this user? This will also delete their rentals and reviews.')">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="6" style="text-align:center;">No users found.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<?php include 'admin-footer.php'; ?>
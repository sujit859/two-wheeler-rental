<?php require 'admin-header.php'; ?>

<div class="dashboard-header">
    <h1>Manage Bookings</h1>
</div>

<?php
// Handle status update
if(isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $booking_id = $_GET['id'];
    $new_status = '';
    $title = '';

    switch($action) {
        case 'approve': $new_status = 'approved'; $title = 'Booking Approved'; break;
        case 'cancel': $new_status = 'cancelled'; $title = 'Booking Cancelled'; break;
        case 'complete': $new_status = 'completed'; $title = 'Booking Completed'; break;
    }

    if($new_status) {
        $stmt = $pdo->prepare("UPDATE rentals SET status = ? WHERE id = ?");
        if($stmt->execute([$new_status, $booking_id])) {
            // Get user_id and vehicle info for notification
            $info = $pdo->prepare("SELECT r.user_id, v.title FROM rentals r JOIN vehicles v ON r.vehicle_id = v.id WHERE r.id = ?");
            $info->execute([$booking_id]);
            $data = $info->fetch();
            if($data) {
                $message = "Your booking for " . $data['title'] . " has been " . $new_status . ".";
                createNotification($data['user_id'], $title, $message);
            }
            $_SESSION['message'] = "Booking status updated to " . $new_status . ".";
        }
    }
    header("Location: manage-bookings.php");
    exit;
}

// Get all bookings
$bookings = $pdo->query("SELECT r.*, v.brand, v.model, v.title as vehicle_title, u.full_name, u.email, u.phone FROM rentals r JOIN vehicles v ON r.vehicle_id = v.id JOIN users u ON r.user_id = u.id ORDER BY r.booking_date DESC")->fetchAll();
?>

<?php if(isset($_SESSION['message'])): ?>
    <div class="success"><?= $_SESSION['message']; unset($_SESSION['message']); ?></div>
<?php endif; ?>

<table class="admin-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>User</th>
            <th>Vehicle</th>
            <th>Rental Period</th>
            <th>Total</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if(count($bookings) > 0): ?>
            <?php foreach($bookings as $b): ?>
            <tr>
                <td><?= $b['id'] ?></td>
                <td>
                    <strong><?= htmlspecialchars($b['full_name']) ?></strong><br>
                    <small><?= $b['email'] ?><br><?= $b['phone'] ?></small>
                </td>
                <td><?= htmlspecialchars($b['brand'] . ' ' . $b['model']) ?></td>
                <td><?= $b['start_date'] ?> to <?= $b['end_date'] ?></td>
                <td>Rs.<?= $b['total_price'] ?></td>
                <td>
                    <span class="status-badge status-<?= $b['status'] ?>">
                        <?= ucfirst($b['status']) ?>
                    </span>
                </td>
                <td>
                    <?php if($b['status'] == 'pending'): ?>
                        <a href="?action=approve&id=<?= $b['id'] ?>" class="btn btn-success btn-sm">Approve</a>
                        <a href="?action=cancel&id=<?= $b['id'] ?>" class="btn btn-outline btn-sm" onclick="return confirm('Cancel this booking?')">Cancel</a>
                    <?php elseif($b['status'] == 'approved'): ?>
                        <a href="?action=complete&id=<?= $b['id'] ?>" class="btn btn-primary btn-sm">Mark Completed</a>
                        <a href="?action=cancel&id=<?= $b['id'] ?>" class="btn btn-outline btn-sm" onclick="return confirm('Cancel this booking?')">Cancel</a>
                    <?php elseif($b['status'] == 'completed'): ?>
                        <span class="text-muted">Completed</span>
                    <?php elseif($b['status'] == 'cancelled'): ?>
                        <span class="text-muted">Cancelled</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="7" style="text-align:center;">No bookings found.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<?php include 'admin-footer.php'; ?>
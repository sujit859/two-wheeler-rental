<?php require 'includes/config.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Get user info
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Get recent rentals
$rentals = $pdo->prepare("SELECT r.*, v.title, v.brand, v.model, v.image FROM rentals r JOIN vehicles v ON r.vehicle_id = v.id WHERE r.user_id = ? ORDER BY r.booking_date DESC LIMIT 5");
$rentals->execute([$user_id]);
$recent_rentals = $rentals->fetchAll();

// Get unread notifications
$unread_notifications = getRecentNotifications($user_id, 10);

include 'includes/header.php';
include 'includes/navbar.php';
?>

<div class="dashboard">
    <aside class="dashboard-sidebar">
        <h3>Welcome, <?= htmlspecialchars($user['full_name']) ?></h3>
        <ul>
            <li><a href="user-dashboard.php">Dashboard</a></li>
            <li><a href="my-rentals.php">My Rentals</a></li>
            <li><a href="profile.php">Edit Profile</a></li>
        </ul>
    </aside>
    <main class="dashboard-main">
        <h2>Dashboard</h2>
        
        <div class="profile-info">
            <h3>Profile Information</h3>
            <p><strong>Name:</strong> <?= htmlspecialchars($user['full_name']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
            <p><strong>Phone:</strong> <?= htmlspecialchars($user['phone']) ?></p>
        </div>

        <div class="recent-rentals">
            <h3>Recent Rentals</h3>
            <?php if(count($recent_rentals) > 0): ?>
                <table>
                    <thead>
                        <tr><th>Vehicle</th><th>Dates</th><th>Total</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                    <?php foreach($recent_rentals as $rental): ?>
                        <tr>
                            <td><?= htmlspecialchars($rental['brand'] . ' ' . $rental['model']) ?></td>
                            <td><?= $rental['start_date'] ?> to <?= $rental['end_date'] ?></td>
                            <td>₹<?= $rental['total_price'] ?></td>
                            <td class="status-<?= $rental['status'] ?>"><?= ucfirst($rental['status']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No rentals yet. <a href="vehicles.php">Browse vehicles</a></p>
            <?php endif; ?>
        </div>

        <div class="notifications-section">
            <h3>Recent Notifications</h3>
            <?php if(count($unread_notifications) > 0): ?>
                <ul class="notification-list">
                <?php foreach($unread_notifications as $notif): ?>
                    <li class="<?= $notif['is_read'] ? 'read' : 'unread' ?>">
                        <strong><?= htmlspecialchars($notif['title']) ?></strong><br>
                        <?= htmlspecialchars($notif['message']) ?><br>
                        <small><?= date('d M Y H:i', strtotime($notif['created_at'])) ?></small>
                    </li>
                <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No notifications.</p>
            <?php endif; ?>
        </div>
    </main>
</div>

<?php include 'includes/footer.php'; ?>
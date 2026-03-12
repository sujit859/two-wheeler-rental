<?php require 'includes/config.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Handle cancellation
if(isset($_GET['cancel']) && is_numeric($_GET['cancel'])) {
    $rental_id = $_GET['cancel'];
    // Check if rental belongs to user and is pending
    $check = $pdo->prepare("SELECT id FROM rentals WHERE id = ? AND user_id = ? AND status = 'pending'");
    $check->execute([$rental_id, $user_id]);
    if($check->fetch()) {
        $pdo->prepare("UPDATE rentals SET status = 'cancelled' WHERE id = ?")->execute([$rental_id]);
        $_SESSION['message'] = "Booking cancelled successfully.";
    }
    header("Location: my-rentals.php");
    exit;
}

// Get all rentals for user
$stmt = $pdo->prepare("SELECT r.*, v.title, v.brand, v.model, v.image FROM rentals r JOIN vehicles v ON r.vehicle_id = v.id WHERE r.user_id = ? ORDER BY r.booking_date DESC");
$stmt->execute([$user_id]);
$rentals = $stmt->fetchAll();

include 'includes/header.php';
include 'includes/navbar.php';
?>

<div class="my-rentals">
    <h2>My Rentals</h2>
    
    <?php if(isset($_SESSION['message'])): ?>
        <div class="success"><?= $_SESSION['message']; unset($_SESSION['message']); ?></div>
    <?php endif; ?>
    
    <?php if(count($rentals) > 0): ?>
        <table class="rentals-table">
            <thead>
                <tr>
                    <th>Vehicle</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Total Price</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($rentals as $rental): ?>
                <tr>
                    <td><?= htmlspecialchars($rental['brand'] . ' ' . $rental['model']) ?></td>
                    <td><?= $rental['start_date'] ?></td>
                    <td><?= $rental['end_date'] ?></td>
                    <td>Rs.<?= $rental['total_price'] ?></td>
                    <td>
                        <span class="status-badge status-<?= $rental['status'] ?>">
                            <?= ucfirst($rental['status']) ?>
                        </span>
                    </td>
                    <td>
                        <?php if($rental['status'] == 'pending'): ?>
                            <a href="?cancel=<?= $rental['id'] ?>" class="btn btn-outline" onclick="return confirm('Are you sure you want to cancel this booking?')">Cancel</a>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>You have no rental requests. <a href="vehicles.php">Browse vehicles</a> to rent one.</p>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
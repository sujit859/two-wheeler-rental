<?php require 'includes/config.php';

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM vehicles WHERE id = ?");
$stmt->execute([$id]);
$vehicle = $stmt->fetch();

if(!$vehicle) {
    header("Location: vehicles.php");
    exit;
}

include 'includes/header.php';
include 'includes/navbar.php';
?>

<div class="vehicle-detail">
    <div class="detail-image">
        <img src="<?= htmlspecialchars($vehicle['image']) ?>" alt="<?= htmlspecialchars($vehicle['title']) ?>">
    </div>
    <div class="detail-info">
        <h1><?= htmlspecialchars($vehicle['title']) ?></h1>
        <p class="brand-model"><?= htmlspecialchars($vehicle['brand'] . ' ' . $vehicle['model'] . ' (' . $vehicle['year'] . ')') ?></p>
        
        <table class="specs">
            <tr><th>Engine</th><td><?= $vehicle['engine_cc'] ?> cc</td></tr>
            <tr><th>Fuel Type</th><td><?= $vehicle['fuel_type'] ?></td></tr>
            <tr><th>Transmission</th><td><?= $vehicle['transmission'] ?></td></tr>
            <tr><th>Mileage</th><td><?= $vehicle['mileage'] ?></td></tr>
            <tr><th>Color</th><td><?= $vehicle['color'] ?></td></tr>
            <tr><th>Location</th><td><?= htmlspecialchars($vehicle['location']) ?>, <?= $vehicle['city'] ?></td></tr>
            <tr><th>Price per day</th><td>Rs.<?= $vehicle['price_per_day'] ?></td></tr>
            <tr><th>Status</th>
                <td>
                    <span class="status-badge status-<?= $vehicle['availability_status'] ?>">
                        <?= ucfirst($vehicle['availability_status']) ?>
                    </span>
                </td>
            </tr>
        </table>
        
        <div class="description">
            <h3>Description</h3>
            <p><?= nl2br(htmlspecialchars($vehicle['description'])) ?></p>
        </div>
        
        <?php if($vehicle['availability_status'] == 'available'): ?>
            <a href="rental-booking.php?vehicle_id=<?= $vehicle['id'] ?>" class="btn btn-success btn-large">Rent Now</a>
        <?php else: ?>
            <button class="btn btn-outline" disabled>Not Available</button>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
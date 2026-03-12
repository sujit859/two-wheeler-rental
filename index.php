<?php require 'includes/config.php'; ?>
<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>

<!-- Hero Section -->
<section class="hero">
    <h1>Ride the Best Two Wheelers</h1>
    <p>Affordable and reliable rentals</p>
    <form method="GET" action="vehicles.php" class="search-form">
        <input type="text" name="location" placeholder="Enter city...">
        <input type="text" name="brand" placeholder="Brand (optional)">
        <button type="submit">Search</button>
    </form>
</section>

<!-- Vehicle Grid -->
<section class="vehicle-grid">
    <h2>Available Vehicles</h2>
    <div class="grid-container">
        <?php
        $stmt = $pdo->query("SELECT * FROM vehicles WHERE availability_status = 'available' ORDER BY created_at DESC LIMIT 8");
        if($stmt->rowCount() > 0):
            while($vehicle = $stmt->fetch()):
        ?>
        <div class="vehicle-card">
            <img src="<?= htmlspecialchars($vehicle['image']) ?>" alt="<?= htmlspecialchars($vehicle['title']) ?>">
            <div class="content">
                <h3><?= htmlspecialchars($vehicle['brand'] . ' ' . $vehicle['model']) ?></h3>
                <p>Engine: <?= $vehicle['engine_cc'] ?> cc</p>
                <p>Location: <?= htmlspecialchars($vehicle['city']) ?></p>
                <div class="price">Rs.<?= $vehicle['price_per_day'] ?> <small>/day</small></div>
                <div class="btn-group">
                    <a href="vehicle-details.php?id=<?= $vehicle['id'] ?>" class="btn btn-outline">View Details</a>
                    <a href="rental-booking.php?vehicle_id=<?= $vehicle['id'] ?>" class="btn btn-success">Rent Now</a>
                </div>
            </div>
        </div>
        <?php
            endwhile;
        else:
        ?>
        <p class="no-vehicles">No vehicles available at the moment.</p>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
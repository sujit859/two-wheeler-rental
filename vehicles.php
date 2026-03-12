<?php require 'includes/config.php'; ?>
<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>

<section class="vehicles-page">
    <aside class="filters">
        <h3>Filter Vehicles</h3>
        <form method="GET" action="vehicles.php">
            <div class="filter-group">
                <label>Brand</label>
                <input type="text" name="brand" value="<?= htmlspecialchars($_GET['brand'] ?? '') ?>">
            </div>
            <div class="filter-group">
                <label>City</label>
                <input type="text" name="city" value="<?= htmlspecialchars($_GET['city'] ?? '') ?>">
            </div>
            <div class="filter-group">
                <label>Min Price (per day)</label>
                <input type="number" name="min_price" value="<?= htmlspecialchars($_GET['min_price'] ?? '') ?>">
            </div>
            <div class="filter-group">
                <label>Max Price</label>
                <input type="number" name="max_price" value="<?= htmlspecialchars($_GET['max_price'] ?? '') ?>">
            </div>
            <div class="filter-group">
                <label>Engine CC (max)</label>
                <input type="number" name="engine_cc" value="<?= htmlspecialchars($_GET['engine_cc'] ?? '') ?>">
            </div>
            <div class="filter-group">
                <label>Fuel Type</label>
                <select name="fuel_type">
                    <option value="">Any</option>
                    <option value="Petrol" <?= (isset($_GET['fuel_type']) && $_GET['fuel_type']=='Petrol')?'selected':'' ?>>Petrol</option>
                    <option value="Diesel" <?= (isset($_GET['fuel_type']) && $_GET['fuel_type']=='Diesel')?'selected':'' ?>>Diesel</option>
                    <option value="Electric" <?= (isset($_GET['fuel_type']) && $_GET['fuel_type']=='Electric')?'selected':'' ?>>Electric</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Apply Filters</button>
            <a href="vehicles.php">Clear Filters</a>
        </form>
    </aside>

    <main class="vehicle-list">
        <h2>All Vehicles</h2>
        <div class="grid-container">
            <?php
            // Build query with filters
            $sql = "SELECT * FROM vehicles WHERE availability_status = 'available'";
            $params = [];

            if(!empty($_GET['brand'])) {
                $sql .= " AND brand LIKE ?";
                $params[] = '%' . $_GET['brand'] . '%';
            }
            if(!empty($_GET['city'])) {
                $sql .= " AND city LIKE ?";
                $params[] = '%' . $_GET['city'] . '%';
            }
            if(!empty($_GET['min_price'])) {
                $sql .= " AND price_per_day >= ?";
                $params[] = $_GET['min_price'];
            }
            if(!empty($_GET['max_price'])) {
                $sql .= " AND price_per_day <= ?";
                $params[] = $_GET['max_price'];
            }
            if(!empty($_GET['engine_cc'])) {
                $sql .= " AND engine_cc <= ?";
                $params[] = $_GET['engine_cc'];
            }
            if(!empty($_GET['fuel_type'])) {
                $sql .= " AND fuel_type = ?";
                $params[] = $_GET['fuel_type'];
            }

            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $vehicles = $stmt->fetchAll();

            if(count($vehicles) > 0):
                foreach($vehicles as $vehicle):
            ?>
            <div class="vehicle-card">
                <img src="<?= htmlspecialchars($vehicle['image']) ?>" alt="<?= htmlspecialchars($vehicle['title']) ?>">
                <div class="content">
                    <h3><?= htmlspecialchars($vehicle['brand'] . ' ' . $vehicle['model']) ?></h3>
                    <p>Engine: <?= $vehicle['engine_cc'] ?> cc</p>
                    <p>Fuel: <?= $vehicle['fuel_type'] ?></p>
                    <p>Location: <?= htmlspecialchars($vehicle['city']) ?></p>
                    <div class="price">Rs.<?= $vehicle['price_per_day'] ?> <small>/day</small></div>
                    <div class="btn-group">
                        <a href="vehicle-details.php?id=<?= $vehicle['id'] ?>" class="btn btn-outline">View Details</a>
                        <a href="rental-booking.php?vehicle_id=<?= $vehicle['id'] ?>" class="btn btn-success">Rent Now</a>
                    </div>
                </div>
            </div>
            <?php
                endforeach;
            else:
                echo "<p>No vehicles match your criteria.</p>";
            endif;
            ?>
        </div>
    </main>
</section>

<?php include 'includes/footer.php'; ?>
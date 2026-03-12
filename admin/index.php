<?php require 'admin-header.php'; ?>

<div class="dashboard-header">
    <h1>Dashboard</h1>
    <a href="add-vehicle.php" class="btn btn-primary">+ Add New Vehicle</a>
</div>

<div class="stats-grid">
    <?php
    $total_vehicles = $pdo->query("SELECT COUNT(*) FROM vehicles")->fetchColumn();
    $total_users = $pdo->query("SELECT COUNT(*) FROM users WHERE user_type='customer'")->fetchColumn();
    $total_bookings = $pdo->query("SELECT COUNT(*) FROM rentals")->fetchColumn();
    $pending_bookings = $pdo->query("SELECT COUNT(*) FROM rentals WHERE status='pending'")->fetchColumn();
    ?>
    <div class="stat-card">
        <div class="stat-icon">🛵</div>
        <div class="stat-detail">
            <h3>Total Vehicles</h3>
            <p><?= $total_vehicles ?></p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">👥</div>
        <div class="stat-detail">
            <h3>Total Users</h3>
            <p><?= $total_users ?></p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">📅</div>
        <div class="stat-detail">
            <h3>Total Bookings</h3>
            <p><?= $total_bookings ?></p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">⏳</div>
        <div class="stat-detail">
            <h3>Pending Bookings</h3>
            <p><?= $pending_bookings ?></p>
        </div>
    </div>
</div>

<div class="recent-section">
    <h2>Recent Bookings</h2>
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Vehicle</th>
                <th>User</th>
                <th>Dates</th>
                <th>Total</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $recent = $pdo->query("SELECT r.*, v.brand, v.model, u.full_name FROM rentals r JOIN vehicles v ON r.vehicle_id = v.id JOIN users u ON r.user_id = u.id ORDER BY r.booking_date DESC LIMIT 10");
            if($recent->rowCount() > 0):
                while($row = $recent->fetch()):
            ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['brand'] . ' ' . $row['model']) ?></td>
                <td><?= htmlspecialchars($row['full_name']) ?></td>
                <td><?= $row['start_date'] ?> to <?= $row['end_date'] ?></td>
                <td>₹<?= $row['total_price'] ?></td>
                <td><span class="status-badge status-<?= $row['status'] ?>"><?= ucfirst($row['status']) ?></span></td>
                <td><a href="manage-bookings.php?view=<?= $row['id'] ?>" class="btn btn-outline btn-sm">View</a></td>
            </tr>
            <?php
                endwhile;
            else:
            ?>
            <tr><td colspan="7" style="text-align:center;">No recent bookings found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include 'admin-footer.php'; ?>
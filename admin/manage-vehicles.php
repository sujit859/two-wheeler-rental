<?php require 'admin-header.php'; ?>

<div class="dashboard-header">
    <h1>Manage Vehicles</h1>
    <a href="add-vehicle.php" class="btn btn-primary">+ Add New Vehicle</a>
</div>

<?php
// Handle delete
if(isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = $_GET['delete'];
    // First get image path to delete file
    $stmt = $pdo->prepare("SELECT image FROM vehicles WHERE id = ?");
    $stmt->execute([$id]);
    $vehicle = $stmt->fetch();
    if($vehicle) {
        // Delete image file if exists
        $image_path = "../" . $vehicle['image'];
        if(file_exists($image_path)) {
            unlink($image_path);
        }
        // Delete from database
        $pdo->prepare("DELETE FROM vehicles WHERE id = ?")->execute([$id]);
        $_SESSION['message'] = "Vehicle deleted successfully.";
    }
    header("Location: manage-vehicles.php");
    exit;
}

// Handle status toggle (optional quick update)
if(isset($_GET['toggle']) && isset($_GET['status']) && is_numeric($_GET['toggle'])) {
    $id = $_GET['toggle'];
    $status = $_GET['status'];
    $allowed = ['available', 'booked', 'maintenance'];
    if(in_array($status, $allowed)) {
        $pdo->prepare("UPDATE vehicles SET availability_status = ? WHERE id = ?")->execute([$status, $id]);
        $_SESSION['message'] = "Status updated.";
    }
    header("Location: manage-vehicles.php");
    exit;
}

// Fetch all vehicles
$vehicles = $pdo->query("SELECT * FROM vehicles ORDER BY created_at DESC")->fetchAll();
?>

<?php if(isset($_SESSION['message'])): ?>
    <div class="success"><?= $_SESSION['message']; unset($_SESSION['message']); ?></div>
<?php endif; ?>

<table class="admin-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Image</th>
            <th>Title</th>
            <th>Brand/Model</th>
            <th>Price/Day</th>
            <th>City</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if(count($vehicles) > 0): ?>
            <?php foreach($vehicles as $v): ?>
            <tr>
                <td><?= $v['id'] ?></td>
                <td>
                    <img src="../<?= htmlspecialchars($v['image']) ?>" alt="<?= htmlspecialchars($v['title']) ?>" style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;">
                </td>
                <td><?= htmlspecialchars($v['title']) ?></td>
                <td><?= htmlspecialchars($v['brand'] . ' ' . $v['model']) ?></td>
                <td>Rs.<?= $v['price_per_day'] ?></td>
                <td><?= htmlspecialchars($v['city']) ?></td>
                <td>
                    <span class="status-badge status-<?= $v['availability_status'] ?>">
                        <?= ucfirst($v['availability_status']) ?>
                    </span>
                </td>
                <td>
                    <a href="edit-vehicle.php?id=<?= $v['id'] ?>" class="btn btn-outline btn-sm">Edit</a>
                    <a href="?delete=<?= $v['id'] ?>" class="btn btn-outline btn-sm delete" onclick="return confirm('Delete this vehicle?')">Delete</a>
                    <!-- Quick status toggle dropdown -->
                    <select onchange="location.href='?toggle=<?= $v['id'] ?>&status='+this.value" class="status-select">
                        <option value="available" <?= $v['availability_status']=='available'?'selected':'' ?>>Available</option>
                        <option value="booked" <?= $v['availability_status']=='booked'?'selected':'' ?>>Booked</option>
                        <option value="maintenance" <?= $v['availability_status']=='maintenance'?'selected':'' ?>>Maintenance</option>
                    </select>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="8" style="text-align:center;">No vehicles found.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<?php include 'admin-footer.php'; ?>
<?php require 'admin-header.php';

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM vehicles WHERE id = ?");
$stmt->execute([$id]);
$vehicle = $stmt->fetch();

if(!$vehicle) {
    header("Location: manage-vehicles.php");
    exit;
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Update logic similar to add but without required image
    // ... (omitted for brevity, but should update all fields)
    // If new image uploaded, handle it.
    // After update, redirect to manage-vehicles.php
}

// Display form with $vehicle values
?>
<h1>Edit Vehicle</h1>
<form method="POST" enctype="multipart/form-data" class="admin-form">
    <!-- All fields pre-filled with $vehicle data -->
    <div class="form-group">
        <label>Title</label>
        <input type="text" name="title" value="<?= htmlspecialchars($vehicle['title']) ?>" required>
    </div>
    <!-- ... other fields ... -->
    <div class="form-group">
        <label>Current Image</label><br>
        <img src="../<?= $vehicle['image'] ?>" width="150"><br>
        <label>Change Image (optional)</label>
        <input type="file" name="image" accept="image/*">
    </div>
    <button type="submit" class="btn">Update Vehicle</button>
</form>
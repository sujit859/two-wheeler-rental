<?php require 'admin-header.php'; ?>

<div class="dashboard-header">
    <h1>Add New Vehicle</h1>
</div>

<?php
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect and sanitize input
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $brand = trim($_POST['brand']);
    $model = trim($_POST['model']);
    $year = intval($_POST['year']);
    $engine_cc = intval($_POST['engine_cc']);
    $fuel_type = $_POST['fuel_type'];
    $transmission = $_POST['transmission'];
    $mileage = trim($_POST['mileage']);
    $color = trim($_POST['color']);
    $location = trim($_POST['location']);
    $city = trim($_POST['city']);
    $price_per_day = floatval($_POST['price_per_day']);
    $availability_status = $_POST['availability_status'];

    $errors = [];

    // Basic validation
    if(empty($title)) $errors[] = "Title is required.";
    if(empty($brand)) $errors[] = "Brand is required.";
    if(empty($model)) $errors[] = "Model is required.";
    if(empty($price_per_day) || $price_per_day <= 0) $errors[] = "Valid price per day is required.";
    if(empty($location)) $errors[] = "Location is required.";
    if(empty($city)) $errors[] = "City is required.";

    // Image upload handling
    $image_path = '';
    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "../assets/uploads/";
        // Create directory if it doesn't exist
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $image_name = time() . '_' . basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is actual image
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if($check === false) {
            $errors[] = "File is not an image.";
        } elseif ($_FILES["image"]["size"] > 5000000) { // 5MB
            $errors[] = "Image size too large (max 5MB).";
        } elseif(!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            $errors[] = "Only JPG, JPEG, PNG & GIF files are allowed.";
        } else {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $image_path = "assets/uploads/" . $image_name;
            } else {
                $errors[] = "Error uploading image.";
            }
        }
    } else {
        $errors[] = "Image is required.";
    }

    // If no errors, insert into database
    if(empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO vehicles (title, description, brand, model, year, engine_cc, fuel_type, transmission, mileage, color, location, city, price_per_day, availability_status, image) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
        if($stmt->execute([$title, $description, $brand, $model, $year, $engine_cc, $fuel_type, $transmission, $mileage, $color, $location, $city, $price_per_day, $availability_status, $image_path])) {
            $_SESSION['message'] = "Vehicle added successfully!";
            header("Location: manage-vehicles.php");
            exit;
        } else {
            $errors[] = "Database error. Please try again.";
        }
    }
}
?>

<?php if(!empty($errors)): ?>
    <div class="error">
        <?php foreach($errors as $error): ?>
            <p><?= htmlspecialchars($error) ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data" class="admin-form">
    <div class="form-row">
        <div class="form-group">
            <label for="title">Vehicle Title *</label>
            <input type="text" id="title" name="title" value="<?= htmlspecialchars($_POST['title'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label for="brand">Brand *</label>
            <input type="text" id="brand" name="brand" value="<?= htmlspecialchars($_POST['brand'] ?? '') ?>" required>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label for="model">Model *</label>
            <input type="text" id="model" name="model" value="<?= htmlspecialchars($_POST['model'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label for="year">Year</label>
            <input type="number" id="year" name="year" min="1900" max="<?= date('Y')+1 ?>" value="<?= htmlspecialchars($_POST['year'] ?? date('Y')) ?>">
        </div>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label for="engine_cc">Engine CC</label>
            <input type="number" id="engine_cc" name="engine_cc" value="<?= htmlspecialchars($_POST['engine_cc'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label for="fuel_type">Fuel Type</label>
            <select id="fuel_type" name="fuel_type">
                <option value="Petrol" <?= (isset($_POST['fuel_type']) && $_POST['fuel_type']=='Petrol')?'selected':'' ?>>Petrol</option>
                <option value="Diesel" <?= (isset($_POST['fuel_type']) && $_POST['fuel_type']=='Diesel')?'selected':'' ?>>Diesel</option>
                <option value="Electric" <?= (isset($_POST['fuel_type']) && $_POST['fuel_type']=='Electric')?'selected':'' ?>>Electric</option>
                <option value="Other" <?= (isset($_POST['fuel_type']) && $_POST['fuel_type']=='Other')?'selected':'' ?>>Other</option>
            </select>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label for="transmission">Transmission</label>
            <select id="transmission" name="transmission">
                <option value="Manual" <?= (isset($_POST['transmission']) && $_POST['transmission']=='Manual')?'selected':'' ?>>Manual</option>
                <option value="Automatic" <?= (isset($_POST['transmission']) && $_POST['transmission']=='Automatic')?'selected':'' ?>>Automatic</option>
            </select>
        </div>
        <div class="form-group">
            <label for="mileage">Mileage</label>
            <input type="text" id="mileage" name="mileage" value="<?= htmlspecialchars($_POST['mileage'] ?? '') ?>">
        </div>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label for="color">Color</label>
            <input type="text" id="color" name="color" value="<?= htmlspecialchars($_POST['color'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label for="price_per_day">Price per day (Rs.) *</label>
            <input type="number" step="0.01" id="price_per_day" name="price_per_day" value="<?= htmlspecialchars($_POST['price_per_day'] ?? '') ?>" required>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label for="location">Location (address) *</label>
            <input type="text" id="location" name="location" value="<?= htmlspecialchars($_POST['location'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label for="city">City *</label>
            <input type="text" id="city" name="city" value="<?= htmlspecialchars($_POST['city'] ?? '') ?>" required>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label for="availability_status">Availability Status</label>
            <select id="availability_status" name="availability_status">
                <option value="available" <?= (isset($_POST['availability_status']) && $_POST['availability_status']=='available')?'selected':'' ?>>Available</option>
                <option value="booked" <?= (isset($_POST['availability_status']) && $_POST['availability_status']=='booked')?'selected':'' ?>>Booked</option>
                <option value="maintenance" <?= (isset($_POST['availability_status']) && $_POST['availability_status']=='maintenance')?'selected':'' ?>>Maintenance</option>
            </select>
        </div>
        <div class="form-group">
            <label for="image">Vehicle Image *</label>
            <input type="file" id="image" name="image" accept="image/*" required>
        </div>
    </div>

    <div class="form-group">
        <label for="description">Description</label>
        <textarea id="description" name="description" rows="5"><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Add Vehicle</button>
        <a href="manage-vehicles.php" class="btn btn-outline">Cancel</a>
    </div>
</form>

<?php include 'admin-footer.php'; ?>
<?php require 'includes/config.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$vehicle_id = $_GET['vehicle_id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM vehicles WHERE id = ? AND availability_status = 'available'");
$stmt->execute([$vehicle_id]);
$vehicle = $stmt->fetch();

if(!$vehicle) {
    $_SESSION['error'] = "Vehicle not available.";
    header("Location: vehicles.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$user_stmt = $pdo->prepare("SELECT full_name, email, phone FROM users WHERE id = ?");
$user_stmt->execute([$user_id]);
$user = $user_stmt->fetch();

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $message = trim($_POST['message']);

    // Validate dates
    $today = date('Y-m-d');
    if($start_date < $today) {
        $error = "Start date cannot be in the past.";
    } elseif($end_date < $start_date) {
        $error = "End date must be after start date.";
    } else {
        // Calculate days (including both start and end)
        $datetime1 = new DateTime($start_date);
        $datetime2 = new DateTime($end_date);
        $interval = $datetime1->diff($datetime2);
        $days = $interval->days + 1;
        $total_price = $days * $vehicle['price_per_day'];

        // Insert rental
        $insert = $pdo->prepare("INSERT INTO rentals (vehicle_id, user_id, renter_name, renter_email, renter_phone, start_date, end_date, total_price, message, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')");
        if($insert->execute([$vehicle_id, $user_id, $user['full_name'], $user['email'], $user['phone'], $start_date, $end_date, $total_price, $message])) {
            $_SESSION['success'] = "Rental request submitted successfully! You will be notified once approved.";
            header("Location: my-rentals.php");
            exit;
        } else {
            $error = "Something went wrong. Please try again.";
        }
    }
}

include 'includes/header.php';
include 'includes/navbar.php';
?>

<div class="booking-form">
    <h2>Rent <?= htmlspecialchars($vehicle['brand'] . ' ' . $vehicle['model']) ?></h2>
    <?php if(isset($error)): ?>
        <div class="error"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" id="bookingForm">
        <div class="form-group">
            <label>Name</label>
            <input type="text" value="<?= htmlspecialchars($user['full_name']) ?>" readonly>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" value="<?= htmlspecialchars($user['email']) ?>" readonly>
        </div>
        <div class="form-group">
            <label>Phone</label>
            <input type="text" value="<?= htmlspecialchars($user['phone']) ?>" readonly>
        </div>
        <div class="form-group">
            <label for="start_date">Start Date</label>
            <input type="date" name="start_date" id="start_date" min="<?= date('Y-m-d') ?>" required>
        </div>
        <div class="form-group">
            <label for="end_date">End Date</label>
            <input type="date" name="end_date" id="end_date" min="<?= date('Y-m-d') ?>" required>
        </div>
        <div class="form-group">
            <label for="message">Message (optional)</label>
            <textarea name="message" id="message" rows="4"></textarea>
        </div>
        <div class="price-display">
            <strong>Total Price:</strong> Rs.<span id="total_price">0</span>
        </div>
        <button type="submit" class="btn">Submit Request</button>
    </form>
</div>

<script>
const pricePerDay = <?= $vehicle['price_per_day'] ?>;
const startInput = document.getElementById('start_date');
const endInput = document.getElementById('end_date');
const totalSpan = document.getElementById('total_price');

function calculateTotal() {
    if(startInput.value && endInput.value) {
        const start = new Date(startInput.value);
        const end = new Date(endInput.value);
        if(end >= start) {
            const diffTime = end - start;
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
            totalSpan.innerText = diffDays * pricePerDay;
        } else {
            totalSpan.innerText = 0;
        }
    }
}

startInput.addEventListener('change', calculateTotal);
endInput.addEventListener('change', calculateTotal);
</script>

<?php include 'includes/footer.php'; ?>
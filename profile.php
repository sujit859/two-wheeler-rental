<?php require 'includes/config.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$error = '';
$success = '';

// Fetch current user data
$stmt = $pdo->prepare("SELECT full_name, email, phone FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate
    if(empty($full_name) || empty($email) || empty($phone)) {
        $error = "All fields are required.";
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {
        // Check if email already exists for another user
        $check = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $check->execute([$email, $user_id]);
        if($check->fetch()) {
            $error = "Email already in use by another account.";
        } else {
            // If password fields are not empty, verify current password
            if(!empty($current_password) || !empty($new_password) || !empty($confirm_password)) {
                // Verify current password
                $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
                $stmt->execute([$user_id]);
                $hashed = $stmt->fetchColumn();

                if(!password_verify($current_password, $hashed)) {
                    $error = "Current password is incorrect.";
                } elseif($new_password !== $confirm_password) {
                    $error = "New passwords do not match.";
                } elseif(strlen($new_password) < 6) {
                    $error = "New password must be at least 6 characters.";
                } else {
                    // Update with new password
                    $new_hashed = password_hash($new_password, PASSWORD_DEFAULT);
                    $update = $pdo->prepare("UPDATE users SET full_name = ?, email = ?, phone = ?, password = ? WHERE id = ?");
                    if($update->execute([$full_name, $email, $phone, $new_hashed, $user_id])) {
                        $_SESSION['user_name'] = $full_name;
                        $success = "Profile updated successfully.";
                        // Refresh user data
                        $user['full_name'] = $full_name;
                        $user['email'] = $email;
                        $user['phone'] = $phone;
                    } else {
                        $error = "Update failed. Please try again.";
                    }
                }
            } else {
                // Update without changing password
                $update = $pdo->prepare("UPDATE users SET full_name = ?, email = ?, phone = ? WHERE id = ?");
                if($update->execute([$full_name, $email, $phone, $user_id])) {
                    $_SESSION['user_name'] = $full_name;
                    $success = "Profile updated successfully.";
                    $user['full_name'] = $full_name;
                    $user['email'] = $email;
                    $user['phone'] = $phone;
                } else {
                    $error = "Update failed. Please try again.";
                }
            }
        }
    }
}

include 'includes/header.php';
include 'includes/navbar.php';
?>

<div class="form-container">
    <h2>Edit Profile</h2>
    <?php if($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if($success): ?>
        <div class="success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="form-group">
            <label for="full_name">Full Name</label>
            <input type="text" name="full_name" id="full_name" value="<?= htmlspecialchars($user['full_name']) ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" value="<?= htmlspecialchars($user['email']) ?>" required>
        </div>
        <div class="form-group">
            <label for="phone">Phone</label>
            <input type="text" name="phone" id="phone" value="<?= htmlspecialchars($user['phone']) ?>" required>
        </div>

        <hr>
        <h3>Change Password (leave blank to keep current)</h3>

        <div class="form-group">
            <label for="current_password">Current Password</label>
            <input type="password" name="current_password" id="current_password">
        </div>
        <div class="form-group">
            <label for="new_password">New Password</label>
            <input type="password" name="new_password" id="new_password">
        </div>
        <div class="form-group">
            <label for="confirm_password">Confirm New Password</label>
            <input type="password" name="confirm_password" id="confirm_password">
        </div>

        <button type="submit" class="btn btn-primary">Update Profile</button>
        <a href="user-dashboard.php" class="btn btn-outline">Cancel</a>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
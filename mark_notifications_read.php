<?php
require 'includes/config.php';

if(isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("UPDATE notifications SET is_read = 1 WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
}

// Return JSON response if AJAX
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    echo json_encode(['success' => true]);
} else {
    header("Location: " . $_SERVER['HTTP_REFERER'] ?? 'index.php');
}
exit;
?>
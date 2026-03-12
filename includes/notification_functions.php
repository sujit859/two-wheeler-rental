<?php
/**
 * Create a notification for a user
 * @param int $user_id
 * @param string $title
 * @param string $message
 */
function createNotification($user_id, $title, $message) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO notifications (user_id, title, message) VALUES (?, ?, ?)");
    $stmt->execute([$user_id, $title, $message]);
}

/**
 * Get unread notification count for a user
 * @param int $user_id
 * @return int
 */
function getUnreadNotificationCount($user_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM notifications WHERE user_id = ? AND is_read = 0");
    $stmt->execute([$user_id]);
    return $stmt->fetchColumn();
}

/**
 * Get recent notifications for a user
 * @param int $user_id
 * @param int $limit
 * @return array
 */
function getRecentNotifications($user_id, $limit = 5) {
    global $pdo;
    // Use bindValue to ensure integer types for LIMIT
    $stmt = $pdo->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT ?");
    $stmt->bindValue(1, $user_id, PDO::PARAM_INT);
    $stmt->bindValue(2, (int)$limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}
?>
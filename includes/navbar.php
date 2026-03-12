<?php
// Call this after config.php include
$current_page = basename($_SERVER['PHP_SELF']);
?>
<nav class="navbar">
    <div class="logo"><a href="index.php">TwoWheelerRent</a></div>
    <ul class="nav-links">
        <li><a href="index.php" <?= $current_page=='index.php'?'class="active"':'' ?>>Home</a></li>
        <li><a href="vehicles.php" <?= $current_page=='vehicles.php'?'class="active"':'' ?>>Vehicles</a></li>
        <li><a href="about.php" <?= $current_page=='about.php'?'class="active"':'' ?>>About</a></li>
        <?php if(isset($_SESSION['user_id'])): ?>
            <li><a href="user-dashboard.php">Dashboard</a></li>
            <li class="notification-icon">
                <a href="#" id="notificationBell">🔔
                    <?php
                    $unread = getUnreadNotificationCount($_SESSION['user_id']);
                    if($unread > 0) echo "<span class='badge'>$unread</span>";
                    ?>
                </a>
                <div class="notification-dropdown" id="notificationDropdown">
                    <?php
                    $notifications = getRecentNotifications($_SESSION['user_id']);
                    if($notifications):
                        foreach($notifications as $notif): ?>
                            <div class="notif-item <?= $notif['is_read'] ? 'read' : 'unread' ?>" data-id="<?= $notif['id'] ?>">
                                <strong><?= htmlspecialchars($notif['title']) ?></strong><br>
                                <small><?= htmlspecialchars($notif['message']) ?></small><br>
                                <small><?= date('d M Y H:i', strtotime($notif['created_at'])) ?></small>
                            </div>
                        <?php endforeach;
                    else: ?>
                        <div class="notif-item">No notifications</div>
                    <?php endif; ?>
                </div>
            </li>
            <li><a href="logout.php">Logout (<?= htmlspecialchars($_SESSION['user_name']) ?>)</a></li>
        <?php else: ?>
            <li><a href="login.php">Login</a></li>
            <li><a href="register.php">Register</a></li>
        <?php endif; ?>
    </ul>
</nav>
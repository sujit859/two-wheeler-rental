<?php
require_once '../includes/config.php';

if(!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'admin') {
    header("Location: ../login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Two Wheeler Rental</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<nav class="admin-navbar">
    <div class="logo"><a href="index.php">Admin Panel</a></div>
    <ul class="nav-links">
        <li><a href="index.php">Dashboard</a></li>
        <li><a href="manage-vehicles.php">Manage Vehicles</a></li>
        <li><a href="manage-bookings.php">Manage Bookings</a></li>
        <li><a href="manage-users.php">Manage Users</a></li>
        <li><a href="../logout.php">Logout</a></li>
    </ul>
</nav>
<main class="admin-main"></main>
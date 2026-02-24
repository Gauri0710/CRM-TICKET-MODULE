<?php
session_start();
include("../config/db.php");

//allow only admin
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: ../auth/login.php");
    exit();
}

$total_users=$conn->query("SELECT COUNT(*) as total FROM users")->fetch_assoc()['total'];
$total_tickets = $conn->query("SELECT COUNT(*) as total FROM tickets")->fetch_assoc()['total'];
$pending_tickets = $conn->query("SELECT COUNT(*) as total FROM tickets WHERE status='pending'")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <!-- Left Sidebar -->
        <div class="sidebar">
            <h2>Admin Panel</h2>
            <a href="index.php?page=dashboard">Dashboard</a>
            <a href="index.php?page=users">Manage Users</a>
            <a href="index.php?page=manage_ticket">Manage Tickets</a>
            <a href="../auth/logout.php">Logout</a>
        </div>

        <!-- Right Content -->
        <div class="content">
            <?php
            // Load page based on sidebar link
            if(isset($_GET['page'])){
                $page = $_GET['page'];
                if($page == 'users'){
                    include("users.php");
                } elseif($page == 'manage_ticket'){
                    include("manage_ticket.php");
                } else {
                    include("dashboard_content.php"); // Default dashboard stats
                }
            } else {
                include("dashboard_content.php"); // Default dashboard stats
            }
            ?>
        </div>
    </div>
</body>
</html>

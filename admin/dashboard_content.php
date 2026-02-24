<?php

$total_users = $conn->query("SELECT COUNT(*) as total FROM users")->fetch_assoc()['total'];
$total_tickets = $conn->query("SELECT COUNT(*) as total FROM tickets")->fetch_assoc()['total'];
$pending_tickets = $conn->query("SELECT COUNT(*) as total FROM tickets WHERE status='pending'")->fetch_assoc()['total'];
?>

<div class="dashboard-content">
    <h2>Welcome, <?= $_SESSION['user_name']; ?>!</h2>
    <p>This is your admin dashboard. Here you can manage users and tickets.</p>

    <div class="stats-container">
        <div class="stat-card">
            <h3>Total Users</h3>
            <p><?= $total_users ?></p>
        </div>

        <div class="stat-card">
            <h3>Total Tickets</h3>
            <p><?= $total_tickets ?></p>
        </div>

        <div class="stat-card">
            <h3>Pending Tickets</h3>
            <p><?= $pending_tickets ?></p>
        </div>
    </div>
</div>
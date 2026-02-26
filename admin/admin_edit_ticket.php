<?php
include("../config/db.php"); 

// Get id from link
if(!isset($_GET['id'])){
    echo "Invalid Ticket Id";
    exit();
}

$id = (int) $_GET['id'];  // secure

// Fetch ticket details
$ticket_sql = $conn->query("SELECT * FROM tickets WHERE id = $id");

if(!$ticket_sql){
    echo "Database Error: " . $conn->error;
    exit();
}

$ticket = $ticket_sql->fetch_assoc();

if(!$ticket){
    echo "Ticket not found!";
    exit();
}

// Fetch all users for reassign
$users = $conn->query("SELECT id, name FROM users");
?>

<style>
.page-edit-ticket-bg {
    background:#e8f4ff;
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center;
    padding: 30px;
    min-height: 90vh;
}
</style>

<div class="page-edit-ticket-bg">

<link rel="stylesheet" href="css/style.css">

<div class="ticket-edit-box">
    <h2>Edit Ticket (Admin)</h2>

    <form action="update_ticket_admin.php" method="POST">
        <input type="hidden" name="ticket_id" value="<?= $ticket['id']; ?>">

        <!-- ✅ FIXED HERE -->
        <label><strong>Ticket Title:</strong></label>
        <p><?= htmlspecialchars($ticket['title'] ?? ''); ?></p>

        <label><strong>Description:</strong></label>
        <p><?= htmlspecialchars($ticket['description'] ?? ''); ?></p>

        <label><strong>Change Status:</strong></label>
        <select name="status">
            <option value="pending" <?= $ticket['status']=="pending" ? "selected" : "" ?>>Pending</option>
            <option value="inprogress" <?= $ticket['status']=="inprogress" ? "selected" : "" ?>>In Progress</option>
            <option value="completed" <?= $ticket['status']=="completed" ? "selected" : "" ?>>Completed</option>
            <option value="onhold" <?= $ticket['status']=="onhold" ? "selected" : "" ?>>On Hold</option>
        </select>

        <br><br>

        <label><strong>Reassign Ticket To:</strong></label>
        <select name="assigned_to">
            <?php while($u = $users->fetch_assoc()) { ?>
                <option value="<?= $u['id']; ?>" 
                    <?= $ticket['assigned_to']==$u['id'] ? "selected" : "" ?>>
                    <?= htmlspecialchars($u['name']); ?>
                </option>
            <?php } ?>
        </select>

        <br><br>

        <button type="submit" class="btn">Update Ticket</button>
    </form>
</div>
</div>
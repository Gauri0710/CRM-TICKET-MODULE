<?php
include("../config/db.php"); 

//get id from link
if(!isset($_GET['id'])){
    echo "Invalid Ticket Id";
    exit();
}

$id=$_GET['id'];

//fetch ticket details

$ticket_sql=$conn->query("SELECT * from tickets WHERE id='$id'");
if(!$ticket_sql){
    echo "Database Error: " . $conn->error;
    exit();
}

$ticket=$ticket_sql->fetch_assoc();

if(!$ticket){
    echo "Ticket not found!";
    exit();
}

//fetch all users for reassign
$users=$conn->query("SELECT id,name FROM users");
?>

<style>
.page-edit-ticket-bg {
    background-image: url('../assets/admin.jpg');
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
    <h2>Edit Ticket(Admin)</h2>
    <form action="update_ticket_admin.php" method="POST">
        <input type="hidden" name="ticket_id" value="<?= $ticket['id']; ?>">

        <label><strong>Ticket Name:</strong></label>
        <p><?= $ticket['name']; ?></p>

        <label><strong>Description:</strong></label>
        <p><?= $ticket['description']; ?></p>

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
                    <?= $u['name']; ?>
                </option>
            <?php } ?>
        </select>

        <br><br>

        <button type="submit" class="btn">Update Ticket</button>
    </form>
</div>
</div>
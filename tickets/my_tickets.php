<?php
session_start();

if(!isset($_SESSION["user_id"])){
    header("Location:../auth/login.php");
    exit();

}
require_once "../config/db.php";

$user_id=$_SESSION["user_id"];
$role=$_SESSION["role"];

//fetching tickets based on role

if($role=="author"){
    $tickets=$conn->query("SELECT t.*, u.name as assignee_name 
                             FROM tickets t 
                             LEFT JOIN users u ON t.assigned_to = u.id 
                             WHERE t.created_by = $user_id
                             ORDER BY t.created_at DESC");

}else if ($role=="assignee"){
    $tickets=$conn->query("SELECT t.*, u.name as author_name 
                             FROM tickets t 
                             LEFT JOIN users u ON t.created_by = u.id 
                             WHERE t.assigned_to = $user_id
                             ORDER BY t.created_at DESC");
}else {
    $tickets=null;

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket List</title>
    <link rel="stylesheet" href="../assets/css/style.css">

</head>
<body>
    <div class="container">
        <div class="card">
            <h2>Ticket List</h2>
            <table border="1" cellpadding="10" cellspacing="0" width="100%">
                <tr>
                    <th>ID</th>
                    <th>title</th>
                    <th>Description</th>
                    <th>Status</th>
                    <?php if($role=="author"): ?>
                    <th>Assigned To</th>
                    <?php else: ?>
                    <th>Author</th>
                    <?php endif; ?>
                    <th>Created At</th>
                    <th>Updated AT</th>
                    <th>Actions</th>
                </tr>
                <?php if($tickets && $tickets->num_rows>0): ?>
                    <?php while($ticket=$tickets->fetch_assoc()): ?>
                        <tr>
                            <td><?= $ticket['id'] ?></td>
                            <td><?= htmlspecialchars($ticket['name']) ?></td>
                            <td><?= htmlspecialchars($ticket['description']) ?></td>
                            <td><?= $ticket['status'] ?></td>
                            <?php if($role=="author"): ?>
                                <td><?= htmlspecialchars($ticket['assignee_name']??'-') ?></td>
                            <?php else: ?>
                                <td><?= htmlspecialchars($ticket['author_name']??'-') ?></td>
                            <?php endif; ?>
                            <td><?= $ticket['created_at'] ?></td>
                            <td><?= $ticket['updated_at'] ?></td>
                            <td>
                                <?php if($role=="author"): ?>
                                    <a href="update_ticket.php?id=<?= $ticket['id'] ?>" class="btn btn-warning">Edit</a>
                                <?php elseif($role=="assignee"): ?>
                                    <a href="update_status.php?id=<?= $ticket['id'] ?>" class="btn btn-success">Update Status</a>
                                <?php endif; ?>
                            </td>
                            </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" style="text-align:center;">No tickets found</td>
                    </tr>
                <?php endif; ?>
            </table>
        <br>
        <a href="../dashboard/index.php" class="btn btn-info">Back</a>
    </div>
</div>
</body>
</html>
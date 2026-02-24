<?php
session_start();
// Redirect if not logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: ../auth/login.php");
    exit();
}

if($_SESSION["role"]!="assignee"){
    echo "You do not have permission to view assigned tickets.";
    exit();
}

require_once "../config/db.php";

$assignee_id=$_SESSION["user_id"];

//Fetch tickets
$query="SELECT t.*,u.name AS author_name FROM tickets t LEFT JOIN users u on t.created_by=u.id WHERE t.assigned_to=$assignee_id ORDER BY t.assigned_at DESC";

$tickets=$conn->query($query);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assigned Tickets</title>
    <link rel="stylesheet" href="../assets/css/style.css">

</head>
<body>
    <div class="container">
        <div class="card">
            <h2>Assigned Tickets</h2>

            <?php if (isset($_GET['success'])): ?>
            <p style="color: green; font-weight: bold;">Ticket updated successfully!</p>
            <?php endif; ?>
            <?php if ($tickets && $tickets->num_rows > 0): ?>
            <table border="1" cellpadding="10" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Ticket ID</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>File</th>
                        <th>Author</th>
                        <th>Assigned At</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($t = $tickets->fetch_assoc()): ?>
                        <tr>
                            <td><?= $t['id'] ?></td>

                            <td><?= htmlspecialchars($t['name']) ?></td>

                            <td><?= htmlspecialchars($t['description']) ?></td>

                            <td>
                                <?php if ($t['file']): ?>
                                    <a href="<?= $t['file'] ?>" target="_blank">View</a>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>

                            <td><?= htmlspecialchars($t['author_name']) ?></td>

                            <td><?= $t['assigned_at'] ?? '-' ?></td>

                            <td><?= ucfirst($t['status']) ?></td>
                            <td>
                            <a href="update_status.php?id=<?= $t['id'] ?>" class="btn btn-success">Update Status</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>   
                </tbody>      
            </table>    
    <?php else: ?>
        <p>No tickets assigned to you.</p>  
    <?php endif; ?>
    <br>
    <a href="../dashboard/index.php" class="btn btn-info">Back</a>
    </div>
    </div>
</body>
</html>



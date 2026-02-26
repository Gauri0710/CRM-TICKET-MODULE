<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: ../auth/login.php");
    exit();
}

require_once "../config/db.php";

$user_id = (int)$_SESSION["user_id"];
$role = $_SESSION["role"];

// Fetch tickets based on role
if ($role == "author") {

    $tickets = $conn->query("
        SELECT t.*, u.name AS assignee_name
        FROM tickets t
        LEFT JOIN users u ON t.assigned_to = u.id
        WHERE t.created_by = $user_id
        ORDER BY t.created_at DESC
    ");

} elseif ($role == "assignee") {

    $tickets = $conn->query("
        SELECT t.*, u.name AS author_name
        FROM tickets t
        LEFT JOIN users u ON t.created_by = u.id
        WHERE t.assigned_to = $user_id
        ORDER BY t.created_at DESC
    ");

} else {
    $tickets = null;
}
?>

<!DOCTYPE html>
<html>
<head>
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
    <th>Title</th>
    <th>Description</th>
    <th>Status</th>
    <?php if ($role == "author"): ?>
        <th>Assigned To</th>
    <?php else: ?>
        <th>Author</th>
    <?php endif; ?>
    <th>Created At</th>
    <th>Updated At</th>
    <th>Actions</th>
</tr>

<?php if ($tickets && $tickets->num_rows > 0): ?>
    <?php while ($ticket = $tickets->fetch_assoc()): ?>
        <tr>
            <td><?php echo $ticket['id']; ?></td>

            <td><?php echo htmlspecialchars($ticket['title'] ?? '-'); ?></td>

            <td><?php echo htmlspecialchars($ticket['description'] ?? '-'); ?></td>

            <td><?php echo htmlspecialchars($ticket['status'] ?? '-'); ?></td>

            <?php if ($role == "author"): ?>
                <td><?php echo htmlspecialchars($ticket['assignee_name'] ?? '-'); ?></td>
            <?php else: ?>
                <td><?php echo htmlspecialchars($ticket['author_name'] ?? '-'); ?></td>
            <?php endif; ?>

            <td><?php echo $ticket['created_at'] ?? '-'; ?></td>

            <td><?php echo $ticket['updated_at'] ?? '-'; ?></td>

            <td>
                <?php if ($role == "author"): ?>
                    <!-- ✅ FIXED EDIT BUTTON -->
                    <a href="update_ticket.php?id=<?php echo urlencode($ticket['id']); ?>">
                        Edit
                    </a>
                <?php elseif ($role == "assignee"): ?>
                    <a href="update_status.php?id=<?php echo urlencode($ticket['id']); ?>">
                        Update Status
                    </a>
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
<a href="../dashboard/index.php">Back</a>

</div>
</div>

</body>
</html>
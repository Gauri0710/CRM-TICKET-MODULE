<?php

if (isset($_GET['msg']) && $_GET['msg'] == 'updated') {
    echo "<p style='color:green; font-weight:bold;'>Ticket Updated Successfully!</p>";
}

// Fetch all tickets with author and assignee names
$sql = "SELECT t.*, 
        u1.name AS created_by_name,
        u2.name AS assigned_to_name
        FROM tickets t
        LEFT JOIN users u1 ON t.created_by = u1.id
        LEFT JOIN users u2 ON t.assigned_to = u2.id
        ORDER BY t.id DESC";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Tickets</title>
</head>
<body>

<div class="manage-container">
    <h2>Admin - Manage Tickets</h2>

    <table class="manage-table" border="1" cellpadding="10" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Created By</th>
            <th>Assigned To</th>
            <th>Status</th>
            <th>File</th>
            <th>Actions</th>
        </tr>

        <?php if ($result && $result->num_rows > 0): ?>
            <?php while($ticket = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $ticket['id'] ?></td>

                <!-- ✅ FIXED HERE -->
                <td><?= htmlspecialchars($ticket['title'] ?? '') ?></td>

                <td><?= htmlspecialchars($ticket['created_by_name'] ?? '') ?></td>

                <td>
                    <?= !empty($ticket['assigned_to_name']) 
                        ? htmlspecialchars($ticket['assigned_to_name']) 
                        : "<span style='color:red'>Not Assigned</span>" ?>
                </td>

                <td><?= htmlspecialchars(ucfirst($ticket['status'] ?? '')) ?></td>

                <td>
                    <?php if (!empty($ticket['file'])): ?>
                        <a href="<?= htmlspecialchars($ticket['file']) ?>" target="_blank">View</a>
                    <?php else: ?>
                        No File
                    <?php endif; ?>
                </td>

                <td>
                    <a href="admin_edit_ticket.php?id=<?= $ticket['id'] ?>" class="btn btn-info">Edit</a>
                </td>

            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="7" style="text-align:center;">No tickets found</td>
            </tr>
        <?php endif; ?>
    </table>

</div>

</body>
</html>
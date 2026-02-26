<?php
session_start();
require_once("../config/db.php");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'assignee') {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$query = "SELECT * FROM tickets WHERE assigned_to = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Assignee Dashboard</title>
    <link rel="stylesheet" href="../admin/css/style.css">
</head>
<body>

<div class="assignee-container">

    <div style="display:flex; justify-content:space-between; align-items:center;">
        <div>
            <h2>Assignee Dashboard</h2>
            <p>Welcome, <strong><?php echo $_SESSION['user_name']; ?></strong></p>
        </div>
        <a href="../auth/logout.php" class="logout-btn">Logout</a>
    </div>

    <h3>Your Assigned Tickets</h3>

    <table class="ticket-table" border="1" cellpadding="8">
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Description</th>
            <th>File</th>
            <th>Status</th>
            <th>Update</th>
        </tr>

        <?php if($result->num_rows > 0) { ?>
            <?php while($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo htmlspecialchars($row['title']); ?></td>
                <td><?php echo htmlspecialchars($row['description']); ?></td>

                <td>
                    <?php if(!empty($row['file'])) { ?>
                        <a href="../<?php echo $row['file']; ?>" target="_blank">View</a>
                    <?php } else { ?>
                        No File
                    <?php } ?>
                </td>

                <td><?php echo ucfirst($row['status']); ?></td>

                <td>
                    <form method="POST" action="update_status.php">
                        <input type="hidden" name="ticket_id" value="<?php echo $row['id']; ?>">

                        <select name="status">
                            <option value="pending" <?php if($row['status']=="pending") echo "selected"; ?>>Pending</option>
                            <option value="inprogress" <?php if($row['status']=="inprogress") echo "selected"; ?>>In Progress</option>
                            <option value="completed" <?php if($row['status']=="completed") echo "selected"; ?>>Completed</option>
                            <option value="onhold" <?php if($row['status']=="onhold") echo "selected"; ?>>On Hold</option>
                        </select>

                        <button type="submit">Update</button>
                    </form>
                </td>
            </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="6">No tickets assigned.</td>
            </tr>
        <?php } ?>

    </table>

</div>

</body>
</html>
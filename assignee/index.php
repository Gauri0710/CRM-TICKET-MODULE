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
    <title>Assignee Panel</title>
    <link rel="stylesheet" href="../admin/css/style.css">
</head>
<body>

<div class="assignee-container">

    <h2>Assignee Panel</h2>
    <p>Welcome, <?php echo $_SESSION['user_name']; ?></p>
    <a href="../auth/logout.php" class="logout-btn">Logout</a>

    <h3>Assigned Tickets</h3>

    <table class="ticket-table">
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Status</th>
        </tr>

        <?php while($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['title']; ?></td>
            <td class="<?php echo $row['status'] == 'Resolved' ? 'status-resolved' : 'status-open'; ?>">
                <?php echo $row['status']; ?>
            </td>
        </tr>
        <?php } ?>

    </table>

</div>

</body>
</html>

<?php
session_start();
if(!isset($_SESSION["user_id"])){
    header("Location: ../auth/login.php");
    exit;
} 
require_once "../config/db.php";
$user_id=$_SESSION['user_id'];
$sql="SELECT COUNT(*) as total FROM tickets where assigned_to=$user_id";
$result=$conn->query($sql);
$row=$result->fetch_assoc();
if($row['total']>0){
    $role='assignee';
}
else{
    $role="author";
}
$_SESSION['role'] = $role; //author/assignee
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body >
<div class="container mt-5">
    <h2>Welcome , <?= $_SESSION["user_name"]; ?></h2>
    <p>Your Role:<strong><?= ucfirst($role); ?></strong></p>
<div class="card p-4 shadow">
<?php if($role=="author"): ?>
    <a href=" ../tickets/create_ticket.php" class="btn btn-primary">Create Ticket</a>
    <a href=" ../tickets/my_tickets.php" class="btn btn-success">View My Tickets</a>
    <a href=" ../tickets/update_ticket.php" class="btn btn-warning">Update My Ticket</a>
<?php elseif($role=="assignee"): ?>
    <a href=" ../tickets/view_assigned_tickets.php" class="btn btn-primary">View Assigned Tickets</a>
    <a href=" ../tickets/update_status.php" class="btn btn-info">Update Ticket Status</a>
<?php endif; ?>

<a href="../auth/logout.php" class="btn btn-danger">Logout</a>
</div>
</div>
    
</body>
</html>
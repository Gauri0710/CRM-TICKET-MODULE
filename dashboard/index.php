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
$role = ($row['total']>0) ? 'assignee' : 'author';
$_SESSION['role'] = $role;
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>CRM Dashboard</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    font-family:'Segoe UI',sans-serif;
    height:100vh;
    background: linear-gradient(135deg,#0f172a,#1e293b,#0f172a);
    overflow:hidden;
    color:white;
}

/* Abstract Background Shapes */
body::before,
body::after{
    content:"";
    position:absolute;
    width:400px;
    height:400px;
    border-radius:50%;
    filter:blur(120px);
    opacity:0.5;
}

body::before{
    background:#3b82f6;
    top:-100px;
    left:-100px;
}

body::after{
    background:#9333ea;
    bottom:-120px;
    right:-120px;
}

/* Main Container */
.container{
    position:relative;
    width:85%;
    margin:80px auto;
    z-index:2;
}

/* Glass Welcome Box */
.welcome-box{
    background:rgba(255,255,255,0.08);
    backdrop-filter:blur(20px);
    padding:35px;
    border-radius:20px;
    margin-bottom:40px;
    box-shadow:0 20px 50px rgba(0,0,0,0.5);
}

.welcome-box h2{
    font-size:28px;
}

.welcome-box p{
    margin-top:10px;
    color:#cbd5e1;
}

/* Dashboard Card */
.dashboard-card{
    background:rgba(255,255,255,0.05);
    backdrop-filter:blur(15px);
    padding:40px;
    border-radius:25px;
    box-shadow:0 25px 60px rgba(0,0,0,0.6);
    text-align:center;
}

/* Buttons */
.btn{
    display:inline-block;
    padding:15px 28px;
    margin:12px;
    border-radius:14px;
    font-weight:600;
    text-decoration:none;
    color:white;
    transition:all .3s ease;
}

.btn:hover{
    transform:translateY(-6px);
    box-shadow:0 15px 30px rgba(0,0,0,0.6);
}

/* Button Colors */
.btn-primary{ background:#2563eb; }
.btn-success{ background:#16a34a; }
.btn-warning{ background:#f59e0b; }
.btn-info{ background:#0891b2; }
.btn-danger{ background:#dc2626; }

</style>
</head>

<body>

<div class="container">

    <div class="welcome-box">
        <h2>Welcome, <?= $_SESSION["user_name"]; ?></h2>
        <p>Your Role: <strong><?= ucfirst($role); ?></strong></p>
    </div>

    <div class="dashboard-card">

        <?php if($role=="author"): ?>
            <a href="../tickets/create_ticket.php" class="btn btn-primary">Create Ticket</a>
            <a href="../tickets/my_tickets.php" class="btn btn-success">View My Tickets</a>
            <a href="../tickets/update_ticket.php" class="btn btn-warning">Update My Ticket</a>
        <?php elseif($role=="assignee"): ?>
            <a href="../tickets/view_assigned_tickets.php" class="btn btn-primary">View Assigned Tickets</a>
            <a href="../tickets/update_status.php" class="btn btn-info">Update Ticket Status</a>
        <?php endif; ?>

        <br>
        <a href="../auth/logout.php" class="btn btn-danger">Logout</a>

    </div>

</div>

</body>
</html>
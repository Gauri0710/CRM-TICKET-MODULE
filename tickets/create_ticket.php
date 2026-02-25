<?php
session_start();

// checking user already login
if (!isset($_SESSION["user_id"])) {
    header("Location: ../auth/login.php");
    exit();
}

// only authors can create tickets
if ($_SESSION["role"] != "author") {
    echo "You do not have permission to create tickets";
    exit();
}

// database connection
require_once "../config/db.php";

// initialize variables
$title = "";
$description = "";
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $title = isset($_POST["title"]) ? trim($_POST["title"]) : "";
    $description = isset($_POST["description"]) ? trim($_POST["description"]) : "";
    $priority = 1; // default priority
    $assigned_to = !empty($_POST["assigned_to"]) ? (int)$_POST["assigned_to"] : NULL;

    if (empty($title) || empty($description)) {
        $message = "Title and description are required";
    } else {

        $created_by = $_SESSION["user_id"];

        // Insert into tickets table
        $stmt = $conn->prepare("INSERT INTO tickets (title, description, priority, created_by, assigned_to) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssiii", $title, $description, $priority, $created_by, $assigned_to);

        if ($stmt->execute()) {

            // update user role if assigned
            if (!empty($assigned_to)) {
                $update_role = $conn->prepare("UPDATE users SET role='assignee' WHERE id=? AND role!='admin'");
                $update_role->bind_param("i", $assigned_to);
                $update_role->execute();
            }

            header("Location: my_tickets.php?success=1");
            exit();

        } else {
            $message = "Something Went Wrong";
        }
    }
}

// fetch users for dropdown
$current_user_id = $_SESSION["user_id"];
$user_result = $conn->query("SELECT id,name FROM users WHERE id != $current_user_id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Ticket</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="container">
<div class="card">

<h2>Create Ticket</h2>

<?php if (!empty($message)): ?>
    <p style="color:red;"><?= $message ?></p>
<?php endif; ?>

<form method="POST">

    <label>Title</label>
    <input type="text" name="title" required><br><br>

    <label>Description</label>
    <textarea name="description" required></textarea><br><br>

    <label>Assigned To (Optional)</label>
    <select name="assigned_to">
        <option value="">--Select User--</option>
        <?php while($user = $user_result->fetch_assoc()): ?>
            <option value="<?= $user['id'] ?>">
                <?= htmlspecialchars($user['name']) ?>
            </option>
        <?php endwhile; ?>
    </select><br><br>

    <button type="submit" class="btn btn-success">Create Ticket</button>
    <a href="../dashboard/index.php" class="btn btn-info">Back</a>

</form>

</div>
</div>

</body>
</html>
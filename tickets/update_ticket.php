<?php


// Redirect if not logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: ../auth/login.php");
    exit();
}

// Only authors can update tickets
if ($_SESSION["role"] !== "author") {
    echo "You do not have permission to edit tickets.";
    exit();
}

require_once "../config/db.php";

$user_id = $_SESSION["user_id"];

// Get ticket ID
$ticket_id = $_GET['id'] ?? null;
if (!$ticket_id) {
    header("Location: my_tickets.php");
    exit();
}

// Fetch ticket (REMOVED created_by condition)
$stmt = $conn->prepare("SELECT * FROM tickets WHERE id=?");
$stmt->bind_param("i", $ticket_id);
$stmt->execute();
$result = $stmt->get_result();
$ticket = $result->fetch_assoc();

if (!$ticket) {
    echo "Ticket not found.";
    exit();
}

$message = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $title = trim($_POST["title"] ?? "");
    $description = trim($_POST["description"] ?? "");
    $assigned_to = !empty($_POST["assigned_to"]) ? $_POST["assigned_to"] : NULL;

    // Validation
    if (empty($title) || empty($description)) {
        $message = "Title and Description are required.";
    } else {

        // REMOVED created_by condition
        $stmt_update = $conn->prepare(
            "UPDATE tickets 
             SET title=?, description=?, assigned_to=? 
             WHERE id=?"
        );

        $stmt_update->bind_param(
            "ssii",
            $title,
            $description,
            $assigned_to,
            $ticket_id
        );

        if ($stmt_update->execute()) {
            header("Location: my_tickets.php?success=1");
            exit();
        } else {
            $message = "Something went wrong. Please try again.";
        }
    }
}

// Fetch users for dropdown
$user_result = $conn->query("SELECT id, name FROM users WHERE id != $user_id");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Ticket</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="container">
<div class="card">

<h2>Update Ticket</h2>

<?php if (!empty($message)): ?>
    <p style="color:red; font-weight:bold;">
        <?= htmlspecialchars($message) ?>
    </p>
<?php endif; ?>

<form method="POST">

<label>Title:</label>
<input type="text" name="title"
       value="<?= htmlspecialchars($ticket["title"] ?? '') ?>"><br><br>

<label>Description:</label>
<textarea name="description"><?= htmlspecialchars($ticket["description"] ?? '') ?></textarea><br><br>

<label>Assign To:</label>
<select name="assigned_to">
    <option value="">--Select Assignee--</option>
    <?php while ($user = $user_result->fetch_assoc()): ?>
        <option value="<?= $user['id'] ?>"
            <?= (($ticket['assigned_to'] ?? '') == $user['id']) ? "selected" : "" ?>>
            <?= htmlspecialchars($user['name']) ?>
        </option>
    <?php endwhile; ?>
</select><br><br>

<button type="submit">Update Ticket</button>
<a href="my_tickets.php">Back</a>

</form>

</div>
</div>

</body>
</html>
<?php
session_start();

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

// Fetch ticket
$stmt = $conn->prepare("SELECT * FROM tickets WHERE id=? AND created_by=?");
$stmt->bind_param("ii", $ticket_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$ticket = $result->fetch_assoc();

if (!$ticket) {
    echo "Ticket not found or permission denied.";
    exit();
}

$message = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST["name"] ?? "");
    $description = trim($_POST["description"] ?? "");
    $assigned_to = $_POST["assigned_to"] ?? NULL;

    $old_assigned_to = $ticket['assigned_to'] ?? NULL;
    $old_assigned_at = $ticket['assigned_at'] ?? NULL;

    // Set assigned_at only if assignee changed
    if ($old_assigned_to != $assigned_to) {
        $assigned_at = !empty($assigned_to) ? date("Y-m-d H:i:s") : NULL;
    } else {
        $assigned_at = $old_assigned_at;
    }

    // File handling
    $file_path = $ticket['file'] ?? NULL;

    if (isset($_FILES["file"]) && $_FILES["file"]["error"] === 0) {

        $upload_dir = "../uploads/";

        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $filename = basename($_FILES["file"]["name"]);
        $file_path = $upload_dir . time() . "_" . $filename;

        if (!move_uploaded_file($_FILES["file"]["tmp_name"], $file_path)) {
            $message = "File upload failed.";
        }
    }

    // Validation
    if (empty($name) || empty($description)) {
        $message = "Title and Description are required.";
    } else {

        $stmt_update = $conn->prepare(
            "UPDATE tickets 
             SET name=?, description=?, file=?, assigned_to=?, assigned_at=? 
             WHERE id=? AND created_by=?"
        );

        $stmt_update->bind_param(
            "sssisii",
            $name,
            $description,
            $file_path,
            $assigned_to,
            $assigned_at,
            $ticket_id,
            $user_id
        );

        if ($stmt_update->execute()) {

            if (!empty($assigned_to)) {
                $update_role = $conn->prepare(
                    "UPDATE users 
                     SET role='assignee' 
                     WHERE id=? AND role!='admin'"
                );
                $update_role->bind_param("i", $assigned_to);
                $update_role->execute();
            }

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

<form method="POST" enctype="multipart/form-data">

<label>Title:</label>
<input type="text" name="name"
       value="<?= htmlspecialchars($ticket["name"] ?? '') ?>"><br><br>

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

<label>File (Optional):</label>
<input type="file" name="file"><br><br>

<?php if (!empty($ticket['file'])): ?>
    <p>
        <strong>Current File:</strong>
        <a href="<?= htmlspecialchars($ticket['file']) ?>" target="_blank">
            <?= basename($ticket['file']) ?>
        </a>
    </p>
<?php endif; ?>

<button type="submit">Update Ticket</button>
<a href="my_tickets.php">Back</a>

</form>

</div>
</div>

</body>
</html>
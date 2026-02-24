<?php


// Handle role change
if(isset($_POST['change_role'])){
    $user_id = $_POST['user_id'];
    $new_role = $_POST['role'];

    // Prevent changing admin role
    $check_admin = $conn->query("SELECT role FROM users WHERE id='$user_id'")->fetch_assoc();
    if($check_admin['role'] != 'admin'){
        $stmt = $conn->prepare("UPDATE users SET role=? WHERE id=?");
        $stmt->bind_param("si", $new_role, $user_id);
        $stmt->execute();
    }
}

// Fetch all users
$result = $conn->query("SELECT id, name, email, role FROM users ORDER BY id ASC");
?>

<div class="users-content">
    <h2>Manage Users</h2>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Change Role</th>
            </tr>
        </thead>
        <tbody>
            <?php if($result->num_rows > 0): ?>
                <?php while($user = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $user['id']; ?></td>
                        <td><?= $user['name']; ?></td>
                        <td><?= $user['email']; ?></td>
                        <td><?= ucfirst($user['role']); ?></td>
                        <td>
                            <?php if($user['role'] != 'admin'): ?>
                                <form method="POST" style="display:flex; gap:5px;">
                                    <input type="hidden" name="user_id" value="<?= $user['id']; ?>">
                                    <select name="role" required>
                                        <option value="author" <?= $user['role']=='author'?'selected':''; ?>>Author</option>
                                        <option value="assignee" <?= $user['role']=='assignee'?'selected':''; ?>>Assignee</option>
                                    </select>
                                    <button type="submit" name="change_role">Update</button>
                                </form>
                            <?php else: ?>
                                <em>Admin</em>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="5">No users found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
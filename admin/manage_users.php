<?php
include '../db_connect.php';  // Ensure this file connects to the database
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// Fetch users from database
$sql = "SELECT id, name, email, role FROM users"; 
$result = $conn->query($sql);

// Check for errors
if (!$result) {
    die("Query failed: " . $conn->error);  // Debugging line to check SQL errors
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="stylesheet" href="manage_users1.css">
</head>
<body>

<h2>👤 Users List</h2>
<p class="typed"><b>Welcome Admin! You can Manage the Users and Delete the User Details..!</b></p>

<div class="table-container">
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Action</th>
        </tr>

        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row['id']); ?></td>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td><?php echo htmlspecialchars($row['role']); ?></td>
                <td class="action-links">
                    <a href="delete_user.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this user?');">
                         Delete
                    </a>
                </td>
            </tr>
        <?php } ?>
    </table>
</div>

<div class="button-container">
    <a href="admin_dashboard.php">
        <button class="custom-button">⬅️ Go Back to Dashboard</button>
    </a>
</div>

</body>
</html>

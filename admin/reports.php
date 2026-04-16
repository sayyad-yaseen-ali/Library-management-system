<?php
session_start();
include __DIR__ . '/db_connect.php';  // Ensure this file exists

// Check if the user is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

?>

<?php include '../includes/header.php'; ?>

<div class="dashboard-container">
    <h2>📊 Activity Reports</h2>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Action</th>
                <th>Timestamp</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Fetch activity logs
            $sql = "SELECT activity_log.id, users.name AS user, activity_log.action, activity_log.timestamp 
                    FROM activity_log 
                    JOIN users ON activity_log.user_id = users.id 
                    ORDER BY activity_log.timestamp DESC";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['user']}</td>
                        <td>{$row['action']}</td>
                        <td>{$row['timestamp']}</td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No activity logs found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include '../includes/footer.php'; ?>

<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}
?>

<?php include '../includes/header.php'; ?>

<link rel="stylesheet" href="admin_dashboard.css">

<div class="dashboard-container">
    <h2>Admin Dashboard</h2>
    <p><b>Welcome Admin..! You Can Manage The Library..!</b></p>
    <ul class="dashboard-menu">
        <li><a href="manage_books.php">📚 Manage Books</a></li>
        <li><a href="manage_users.php">👤 Manage Users</a></li>
        <li><a href="view_issued.php">📄 View Issued Books</a></li>
        <li><a href="../logout.php">🚪 Logout</a></li>
    </ul>
</div>

<?php include '../includes/footer.php'; ?>

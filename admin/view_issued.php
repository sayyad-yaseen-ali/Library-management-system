<?php
include '../db_connect.php';  // Ensure this file connects to the database
session_start();

// Check if admin is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// Fetch issued books along with fine details
$query = "SELECT issued_books.id, users.name AS student_name, books.title, 
                 issued_books.issue_date, issued_books.due_date, issued_books.fine 
          FROM issued_books 
          JOIN users ON issued_books.user_id = users.id 
          JOIN books ON issued_books.book_id = books.id";

$result = $conn->query($query);

if (!$result) {
    die("Query failed: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Issued Books - Admin</title>
    <link rel="stylesheet" href="view_issued1.css">
</head>
<body>

<h2>📄Issued Books List</h2>
<p class="typed"><b>Welcome Admin! You can Manage the Issued Books for the Students..!</b></p>
<div class="table-container">
    <table border="1">
        <tr>
            <th>Student Name</th>
            <th>Book Title</th>
            <th>Issue Date</th>
            <th>Due Date</th>
            <th>Fine</th>
        </tr>
        
        <?php if ($result->num_rows > 0) { ?>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['student_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                    <td><?php echo htmlspecialchars($row['issue_date']); ?></td>
                    <td><?php echo htmlspecialchars($row['due_date']); ?></td>
                    <td><?php echo isset($row['fine']) ? htmlspecialchars($row['fine']) : "0"; ?></td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="5">No books are issued yet.</td>
            </tr>
        <?php } ?>
    </table>
</div>

<div class="button-container">
    <a href="admin_dashboard.php"><button class="custom-button">⬅️ Go Back to Dashboard</button></a>
</div>

</body>
</html>
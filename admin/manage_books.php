<?php
include '../db_connect.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// Fetch books from database
$sql = "SELECT id, title, author, edition, publish_date FROM books"; 
$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book List</title>
    <link rel="stylesheet" href="manage_books.css"> <!-- Linking the CSS file -->
</head>
<body>

<h2>📚 Book List</h2>
<p class="typed"><b>Welcome Admin! Manage books by Adding, Editing, or Deleting them!</b></p>
<!-- Table Container -->
<div class="table-container">
    <table>
        <tr>
            <th>Book ID</th>
            <th>Title</th>
            <th>Author</th>
            <th>Edition</th>
            <th>Year Published</th>
            <th>Action</th>
        </tr>

        <?php 
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                    <td><?php echo htmlspecialchars($row['author']); ?></td>
                    <td><?php echo htmlspecialchars($row['edition']); ?></td>
                    <td><?php echo htmlspecialchars($row['publish_date']); ?></td>
                    <td class="action-links">
                        <a href="edit_book.php?id=<?php echo $row['id']; ?>">Edit</a>
                        <a href="delete_book.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure?');">Delete</a>
                    </td>
                </tr>
            <?php } 
        } else { ?>
            <!-- Show message if no books found -->
            <tr>
                <td colspan="6" class="no-books">📢 No books available in the library!</td>
            </tr>
        <?php } ?>
    </table>
</div>

<!-- Button Container -->
<div class="button-container">
    <a href="add_book.php"><button class="custom-button">➕ Add New Book</button></a>
    <a href="admin_dashboard.php"><button class="custom-button">⬅️ Go Back to Dashboard</button></a>
</div>

</body>
</html>

<?php $conn->close(); ?>  

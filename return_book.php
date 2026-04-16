<?php
session_start();
include __DIR__ . '/db_connect.php';  // Ensure this file exists

// Check if user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['user_id'])) {
    echo "<script>alert('Invalid request!'); window.location='stu_dashboard.php';</script>";
    exit();
}

$user_id = $_GET['user_id'];

// Fetch books borrowed by the student
$query = "SELECT books.id, books.title, books.author, issued_books.issue_date, issued_books.due_date
          FROM issued_books 
          JOIN books ON issued_books.book_id = books.id 
          WHERE issued_books.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// If no books are borrowed
if ($result->num_rows === 0) {
    echo "<script>alert('You have not borrowed any books.'); window.location='stu_dashboard.php';</script>";
    exit();
}

include 'includes/header.php';
?>

<h2> 📚 Return a Book</h2>
<link rel="stylesheet" href="return_book.css">
<table border="1">
    <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Author</th>
        <th>Issue Date</th>
        <th>Due Date</th>
        <th>Action</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo htmlspecialchars($row['title']); ?></td>
            <td><?php echo htmlspecialchars($row['author']); ?></td>
            <td><?php echo $row['issue_date']; ?></td>
            <td><?php echo $row['due_date']; ?></td>
            <td>
                <form method="post" action="process_return.php">
                    <input type="hidden" name="book_id" value="<?php echo $row['id']; ?>">
                    <button type="submit" name="return">Return</button>
                </form>
            </td>
        </tr>
    <?php } ?>
</table>

<!-- Go Back Link -->
<div style="margin-top: 20px;">
    <a href="stu_dashboard.php" class="go-dashboard">⬅️ Go Back to Dashboard</a>
</div>

<?php include 'includes/footer.php'; ?>

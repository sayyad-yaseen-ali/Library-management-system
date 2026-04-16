<?php
session_start();
include __DIR__ . '/db_connect.php';  // Ensure this file exists

// Check if user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle book borrowing
if (isset($_POST['borrow'])) {
    $book_id = $_POST['book_id'];

    // Check if the book is already borrowed by the student
    $check_query = "SELECT * FROM issued_books WHERE book_id = ? AND user_id = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("ii", $book_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('You have already borrowed this book!');</script>";
    } else {
        // Set issue date and due date (7 days from issue)
        $issue_date = date('Y-m-d');
        $due_date = date('Y-m-d', strtotime("+7 days"));

        // Insert into issued_books table
        $insert_query = "INSERT INTO issued_books (book_id, user_id, issue_date, due_date) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("iiss", $book_id, $user_id, $issue_date, $due_date);

        if ($stmt->execute()) {
            echo "<script>alert('Book borrowed successfully!'); window.location='books.php';</script>";
        } else {
            echo "<script>alert('Error borrowing book.');</script>";
        }
    }
}

// Fetch all available books
$query = "SELECT * FROM books";
$result = $conn->query($query);

// Fetch books borrowed by the student
$borrowed_books_query = "SELECT book_id FROM issued_books WHERE user_id = ?";
$stmt = $conn->prepare($borrowed_books_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$borrowed_books_result = $stmt->get_result();

$borrowed_books = [];
while ($row = $borrowed_books_result->fetch_assoc()) {
    $borrowed_books[] = $row['book_id'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Books</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<h2> 📚 Available Books</h2>
<link rel="stylesheet" href="books.css">
<div class="table-container">
    <table>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Author</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr class="<?php echo in_array($row['id'], $borrowed_books) ? 'borrowed' : ''; ?>">
                <td><?php echo $row['id']; ?></td>
                <td><?php echo htmlspecialchars($row['title']); ?></td>
                <td><?php echo htmlspecialchars($row['author']); ?></td>
                <td>
                    <?php if (in_array($row['id'], $borrowed_books)) { ?>
                        <button class="borrowed-btn" disabled>Borrowed</button>
                    <?php } else { ?>
                        <form method="post">
                            <input type="hidden" name="book_id" value="<?php echo $row['id']; ?>">
                            <button type="submit" name="borrow" class="borrow-btn">Borrow</button>
                        </form>
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>
    </table>
</div>

<!-- Button Container -->
<div class="button-container">
    <a href="stu_dashboard.php"><button class="custom-button">⬅️ Go Back to Dashboard</button></a>
</div>

</body>
</html>

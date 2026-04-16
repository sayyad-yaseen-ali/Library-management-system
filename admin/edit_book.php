<?php
include '../db_connect.php';  // Ensure this file exists
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// Validate and sanitize book ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid book ID.");
}
$book_id = intval($_GET['id']); // Convert to integer for safety

// Fetch book data using a prepared statement
$stmt = $conn->prepare("SELECT * FROM books WHERE id = ?");
$stmt->bind_param("i", $book_id);
$stmt->execute();
$result = $stmt->get_result();
$book = $result->fetch_assoc();

if (!$book) {
    die("Book not found.");
}

$stmt->close();

// Update book
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $author = trim($_POST['author']);
    $edition = trim($_POST['edition']);
    $publish_date = trim($_POST['publish_date']);

    // Validate input
    if (empty($title) || empty($author) || empty($edition) || empty($publish_date)) {
        $message = "❌ Please fill in all fields.";
    } else {
        // Use prepared statement for updating
        $stmt = $conn->prepare("UPDATE books SET title = ?, author = ?, edition = ?, publish_date = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $title, $author, $edition, $publish_date, $book_id);

        if ($stmt->execute()) {
            header("Location: manage_books.php");
            exit();
        } else {
            $message = "❌ Error: " . $stmt->error;
        }

        $stmt->close();
    }
}
?>

<?php include '../includes/header.php'; ?>
<link rel="stylesheet" href="edit_book.css">
<h2>Edit Book</h2>

<?php if (!empty($message)) { echo "<p style='color: red;'>$message</p>"; } ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<form method="post">
    <label><i class="fa-solid fa-book"></i> Title</label>
    <input type="text" name="title" value="<?php echo htmlspecialchars($book['title']); ?>" required>
    
    <label><i class="fa-solid fa-user"></i> Author</label>
    <input type="text" name="author" value="<?php echo htmlspecialchars($book['author']); ?>" required>

    <label><i class="fa-solid fa-layer-group"></i> Edition</label>
    <input type="text" name="edition" value="<?php echo htmlspecialchars($book['edition']); ?>" required>

    <label><i class="fa-solid fa-calendar-days"></i> Publishing Date</label>
    <input type="date" name="publish_date" value="<?php echo htmlspecialchars($book['publish_date']); ?>" required>

    <button type="submit"><i class="fa-solid fa-pen-to-square"></i> Update Book</button>
</form>


<a href="manage_books.php" class="back-button">⬅ Back</a>


<?php include '../includes/footer.php'; ?>

<?php
include '../db_connect.php';  // Ensure this file connects to the database
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

$message = ""; // Feedback message

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form values and sanitize them
    $title = trim(htmlspecialchars($_POST['title']));
    $author = trim(htmlspecialchars($_POST['author']));
    $edition = trim(htmlspecialchars($_POST['edition']));
    $publish_date = trim($_POST['publish_date']);

    // Validate input fields
    if (empty($title) || empty($author) || empty($edition) || empty($publish_date)) {
        $message = "❌ Please fill in all fields.";
    } else {
        // Insert into database using a prepared statement
        $stmt = $conn->prepare("INSERT INTO books (title, author, edition, publish_date) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $title, $author, $edition, $publish_date);

        if ($stmt->execute()) {
            $message = "✅ Book added successfully!";
        } else {
            $message = "❌ Error: " . $stmt->error;
        }

        $stmt->close();
    }
}
?>

<?php include '../includes/header.php'; ?>
<link rel="stylesheet" href="add_book.css">

<h2 class="glow-text">Add New Book</h2>

<!-- Display feedback messages -->
<?php if (!empty($message)) { echo "<p class='message'>$message</p>"; } ?>

<!-- Include Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<!-- Form for Adding Books -->
<div class="form-container">
    <form id="book-form" method="post">
        <label><i class="fa-solid fa-book"></i> Book Title</label>
        <input type="text" name="title" required>

        <label><i class="fa-solid fa-user"></i> Author</label>
        <input type="text" name="author" required>

        <label><i class="fa-solid fa-layer-group"></i> Edition</label>
        <input type="text" name="edition" required>

        <label><i class="fa-solid fa-calendar-days"></i> Publishing Date</label>
        <input type="date" name="publish_date" required>
    </form>
</div>


<!-- Buttons: Add Book & Back -->
<div class="button-container">
    <a href="manage_books.php"><button class="custom-button">⬅️ Go Back to Manage Books</button></a>
    <button class="glow-button" onclick="submitForm()">📖 Add Book</button>
</div>

<!-- JavaScript to Submit Form -->
<script>
    function submitForm() {
        document.getElementById("book-form").submit();
    }
</script>

<?php include '../includes/footer.php'; ?>

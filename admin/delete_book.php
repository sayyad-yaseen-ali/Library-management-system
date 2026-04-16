<?php
include '../db_connect.php';
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

// Prepare and execute the DELETE query
$stmt = $conn->prepare("DELETE FROM books WHERE id = ?");
$stmt->bind_param("i", $book_id);

if ($stmt->execute()) {
    header("Location: manage_books.php?message=Book deleted successfully");
    exit();
} else {
    die("Error deleting book: " . $stmt->error);
}

$stmt->close();
$conn->close();
?>

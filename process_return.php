<?php
session_start();
include __DIR__ . '/db_connect.php';  // Ensure this file exists

// Check if user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Check if book_id is provided
if (!isset($_POST['book_id'])) {
    echo "<script>alert('Invalid request!'); window.location='stu_dashboard.php';</script>";
    exit();
}

$book_id = $_POST['book_id'];

// Fetch issue details
$query = "SELECT issue_date, due_date FROM issued_books WHERE book_id = ? AND user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $book_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<script>alert('You have not borrowed this book.'); window.location='stu_dashboard.php';</script>";
    exit();
}

$row = $result->fetch_assoc();
$due_date = new DateTime($row['due_date']);
$return_date = new DateTime();
$fine_amount = 0;
$grace_period = 10;

if ($return_date > $due_date) {
    $interval = $due_date->diff($return_date);
    $days_late = $interval->days;

    if ($days_late > $grace_period) {
        $extra_days = $days_late - $grace_period;
        $fine_amount = $extra_days * 2; // ₹2 per day after grace period
    }
}

// Remove book from issued_books
$delete_query = "DELETE FROM issued_books WHERE book_id = ? AND user_id = ?";
$stmt = $conn->prepare($delete_query);
$stmt->bind_param("ii", $book_id, $user_id);
$stmt->execute();

// Log the return
$insert_log = "INSERT INTO activity_log (user_id, book_id, action, fine_amount, return_date) VALUES (?, ?, 'Returned', ?, ?)";
$stmt = $conn->prepare($insert_log);
$return_date_str = $return_date->format('Y-m-d');
$stmt->bind_param("iiis", $user_id, $book_id, $fine_amount, $return_date_str);
$stmt->execute();

echo "<script>alert('Book returned successfully! Fine: ₹$fine_amount'); window.location='stu_dashboard.php';</script>";
?>

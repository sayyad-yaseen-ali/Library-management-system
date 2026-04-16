<?php
include __DIR__ . '/db_connect.php';  // Ensure this file exists
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// Check if book ID is provided
if (isset($_GET['id'])) {
    $issued_id = intval($_GET['id']);
    
    // Fetch issue details
    $sql = "SELECT issue_date, due_date FROM issued_books WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $issued_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $due_date = new DateTime($row['due_date']);
        $return_date = new DateTime(); // Current date
        
        $fine = 0;
        if ($return_date > $due_date) {
            $interval = $due_date->diff($return_date)->days;
            $fine = $interval * 5; // ₹5 per day
        }

        // Update return date and fine
        $update_sql = "UPDATE issued_books SET return_date = NOW(), fine = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("ii", $fine, $issued_id);
        
        if ($update_stmt->execute()) {
            header("Location: view_issued.php?success=returned");
            exit();
        } else {
            die("Error updating record: " . $conn->error);
        }
    } else {
        die("Invalid book ID.");
    }
} else {
    header("Location: view_issued.php?error=invalid");
    exit();
}
?>

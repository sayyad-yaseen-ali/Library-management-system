<?php
include '../db_connect.php';  // Ensure this file connects to the database
session_start();

// Check if admin is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// Get user ID from URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Delete user from database
    $query = "DELETE FROM users WHERE id = $id";
    if ($conn->query($query)) {
        echo "<script>alert('User deleted successfully!'); window.location='manage_users.php';</script>";
    } else {
        echo "<script>alert('Error deleting user!'); window.location='manage_users.php';</script>";
    }
} else {
    header("Location: manage_users.php");
}
?>

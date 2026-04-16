<?php
session_start();
include __DIR__ . '/db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Check if form data is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $reg_no = $_POST['reg_no'];
    $branch = $_POST['branch'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // Update user details in the database
    $query = "UPDATE users SET name=?, reg_no=?, branch=?, email=?, phone=? WHERE id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssssi", $name, $reg_no, $branch, $email, $phone, $user_id);

    if ($stmt->execute()) {
        echo "<script>alert('Account updated successfully!'); window.location='stu_dashboard.php';</script>";
    } else {
        echo "<script>alert('Error updating account.'); window.location='account_settings.php';</script>";
    }

    $stmt->close();
}
?>

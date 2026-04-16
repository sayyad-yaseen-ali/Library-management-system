<?php
session_start();
include __DIR__ . '/db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Check if a file is uploaded
if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
    $upload_dir = __DIR__ . "/uploads/"; // Absolute path

    // Create uploads directory if it doesn't exist
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $file_tmp = $_FILES['profile_picture']['tmp_name'];
    $file_name = time() . "_" . basename($_FILES['profile_picture']['name']); // Unique file name
    $file_path = $upload_dir . $file_name;

    // Debugging output
    if (!file_exists($file_tmp)) {
        die("Error: Temp file does not exist!");
    }

    // Move uploaded file
    if (move_uploaded_file($file_tmp, $file_path)) {
        // Store file path in database
        $db_path = "uploads/" . $file_name; // Relative path for database
        $query = "UPDATE users SET profile_picture = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $db_path, $user_id);
        if ($stmt->execute()) {
            echo "<script>alert('Profile picture updated successfully!'); window.location='account_settings.php';</script>";
        } else {
            echo "<script>alert('Database update failed!');</script>";
        }
    } else {
        echo "<script>alert('Failed to upload file! Check permissions.');</script>";
    }
} else {
    echo "<script>alert('No file uploaded or an error occurred!');</script>";
}

?>

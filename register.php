<?php
include __DIR__ . '/db_connect.php';  // Ensure this file exists
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $reg_no = trim($_POST['reg_no']);
    $branch = trim($_POST['branch']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $role = "student"; // Default role for users

    // Validate input fields
    if (empty($name) || empty($reg_no) || empty($branch) || empty($email) || empty($phone) || empty($password) || empty($confirm_password)) {
        echo "<script>alert('All fields are required!');</script>";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format!');</script>";
    } elseif (!preg_match("/^[6-9]\d{9}$/", $phone)) {
        echo "<script>alert('Invalid phone number! Must be 10 digits starting with 6-9.');</script>";
    } elseif (strlen($password) < 6) {
        echo "<script>alert('Password must be at least 6 characters long!');</script>";
    } elseif ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match!');</script>";
    } else {
        // Hash password before storing
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Check if email or registration number already exists
        $checkQuery = "SELECT * FROM users WHERE email = ? OR reg_no = ?";
        $stmt = $conn->prepare($checkQuery);
        $stmt->bind_param("ss", $email, $reg_no);
        $stmt->execute();
        $checkResult = $stmt->get_result();

        if ($checkResult->num_rows > 0) {
            echo "<script>alert('Email or Registration Number already registered!');</script>";
        } else {
            // Insert new user
            $query = "INSERT INTO users (name, reg_no, branch, email, phone, password, role) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sssssss", $name, $reg_no, $branch, $email, $phone, $hashed_password, $role);

            if ($stmt->execute()) {
                echo "<script>alert('Registration successful! Redirecting to login page...'); window.location.href='login.php';</script>";
            } else {
                echo "<script>alert('Error: " . $conn->error . "');</script>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Library Management</title>
    <link rel="stylesheet" href="assets/register.css">
</head>
<body>

<div class="container">
    <h2>User Registration</h2>
    <form method="POST">
        <label>Full Name</label>
        <input type="text" name="name" required>

        <label>Registration Number</label>
        <input type="text" name="reg_no" required>

        <label>Branch</label>
        <select name="branch" required>
            <option value="" disabled selected>Select Your Branch</option>
            <option value="CSE">CSE</option>
            <option value="EEE">EEE</option>
            <option value="MECH">MECH</option>
            <option value="AIDS">AIDS</option>
            <option value="CSM">CSM</option>
            <option value="IT">IT</option>
            <option value="ECE">ECE</option>
            <option value="CIVIL">CIVIL</option>
            <option value="AES">AES</option>
        </select>

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Phone Number</label>
        <input type="text" name="phone" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <label>Confirm Password</label>
        <input type="password" name="confirm_password" required>

        <button type="submit">Register</button>
    </form>

    <p>Already have an account? <a href="login.php">Login here</a></p>
</div>

</body>
</html>

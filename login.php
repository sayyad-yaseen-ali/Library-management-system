<?php
session_start();
include __DIR__ . '/db_connect.php';  // Ensure this file exists

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);  
    $password = trim($_POST['password']);

    if (!empty($email) && !empty($password)) {
        // Prepare SQL query
        $query = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            $stored_password = $user['password'];

            // ✅ Check if password is hashed
            if (password_verify($password, $stored_password) || $password === $stored_password) {  
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['email'] = $user['email'];

                if ($user['role'] == 'admin') {
                    header("Location: admin/admin_dashboard.php");
                } else {
                    header("Location: /library_management/stu_dashboard.php");
                }
                exit();
            } else {
                echo "<script>alert('❌ Invalid Password!'); window.location.href='login.php';</script>";
            }
        } else {
            echo "<script>alert('❌ Invalid Email!'); window.location.href='login.php';</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('❌ Please fill in all fields!'); window.location.href='login.php';</script>";
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Library Management</title>
    <link rel="stylesheet" href="assets/logins.css">
</head>
<body>

<div class="container">
    <h2>User Login</h2>
    <form method="POST">
        <label>Email</label>
        <input type="email" name="email" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit">Login</button>
    </form>

    <p>Don't have an account? <a href="register.php">Register here</a></p>
</div>

</body>
<?php
$servername = "localhost";  // Default XAMPP server
$username = "root";  // Default XAMPP username
$password = "";  // Default (empty) password
$dbname = "library_db";  // Ensure this matches your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>
<?php
session_start();
include __DIR__ . '/db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details
$query = "SELECT name, email, phone, reg_no, branch, profile_picture FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Default profile picture if not set
$profile_picture = !empty($user['profile_picture']) ? $user['profile_picture'] : "uploads/default.png";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Account</title>
    <link rel="stylesheet" href="account_settings.css">
</head>
<body>

<h2>👤 Manage Your Account</h2>

<div class="account-container">
    <!-- Profile Picture Upload -->
    <div class="profile-section">
        <img src="<?php echo htmlspecialchars($profile_picture) . '?' . time(); ?>" alt="Profile Picture" class="profile-img">
        <form action="upload_profile.php" method="post" enctype="multipart/form-data">
            <input type="file" name="profile_picture" accept="image/*" required>
            <button type="submit">Upload</button>
        </form>
    </div>

    <!-- Account Update Form -->
    <form method="post" action="update_account.php">
        <label>Name:</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>

        <label>Registration No:</label>
        <input type="text" name="reg_no" value="<?php echo htmlspecialchars($user['reg_no']); ?>" required>

        <label>Branch:</label>
        <input type="text" name="branch" value="<?php echo htmlspecialchars($user['branch']); ?>" required>

        <label>Email:</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

        <label>Phone:</label>
        <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required>

        <button type="submit">Update</button>
    </form>
</div>

</body>
</html>

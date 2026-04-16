<?php
session_start();

// Check if user is logged in and has the correct role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    header("Location: login.php");
    exit();
}

// Prevent browser back button after logout
header("Cache-Control: no-cache, must-revalidate, no-store, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

// Include database connection
include __DIR__ . '/db_connect.php'; 

// Fetch the logged-in user's details
$user_id = $_SESSION['user_id'];
$query = "SELECT name, profile_picture FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($username, $profile_picture);
$stmt->fetch();
$stmt->close();

// Store the username in session
$_SESSION['username'] = strtoupper($username); // Convert to uppercase

// Default profile picture if not set
if (!$profile_picture) {
    $profile_picture = "uploads/default.png"; 
}

// Include header
include 'includes/header.php';
?>

<!-- Link the external CSS file -->
<link rel="stylesheet" href="stu_dashboards.css">



<div class="dashboard-container">
    <h2>Student Dashboard</h2>

    <!-- Welcome Message -->
    <p id="typing-text"><b>Welcome <strong><?php echo $_SESSION['username']; ?></strong>..! You Can Explore The Library..!</b></p>

    <!-- Navigation Menu -->
    <ul class="dashboard-menu">
        <li><a href="books.php">🔍 Search Books</a></li>
        <li><a href="return_book.php?user_id=<?php echo $_SESSION['user_id']; ?>">📦 Return Books</a></li>
        <li><a href="account_settings.php">⚙️ Manage Account</a></li>
        <li><a href="logout.php">🚪 Logout</a></li>
    </ul>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const welcomeText = document.getElementById("typing-text");
        welcomeText.classList.add("typed");
    });
</script>

<?php 
// Include footer
include 'includes/footer.php';
?>

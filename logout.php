<?php
session_start();
session_unset();
session_destroy();

// Prevent browser back button from reopening the page
header("Cache-Control: no-cache, must-revalidate, no-store, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

header("Location: login.php");
exit();
?>

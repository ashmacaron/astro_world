<?php
// Start the session
session_start();

// Destroy the session to log out the user
session_unset(); // Unset all session variables
session_destroy(); // Destroy the session

// Redirect with a delay to the main page
echo "Successfully logged out, redirecting to main page...";

header("refresh:2;url=index.html"); // Redirect to index.php after 2 seconds
exit();
?>

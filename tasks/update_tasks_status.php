<?php
session_start();

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username']; // Use session if available
} elseif (isset($_COOKIE['username'])) {
    $username = $_COOKIE['username']; // Use cookie if session doesn't exist
} else {
    $username = "Guest"; // Fallback for anonymous access
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mentalhealthapp";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Validate the input
    if (isset($_POST['task_id']) && is_numeric($_POST['task_id'])) {
        $task_id = (int)$_POST['task_id'];

        // Update the is_complete column for the given task ID and user ID
        $stmt = $conn->prepare("UPDATE tasks SET is_complete = 1 WHERE ID = ? AND user_id = ?");
        $stmt->bind_param("ii", $task_id, $user_id);

        if ($stmt->execute()) {
            echo "Task updated successfully";
        } else {
            echo "Error updating task: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Invalid task ID";
    }
} else {
    echo "User not logged in";
}

$conn->close();
?>

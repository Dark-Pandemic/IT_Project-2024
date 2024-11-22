<?php
session_start();

if (!isset($_SESSION['ID'])) {
    http_response_code(401);
    echo 'User not logged in';
    exit;


// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mentalhealthapp";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user ID from session and task ID from POST request
$user_id = $_SESSION['ID'];
$task_id = isset($_POST['task_id']) ? (int)$_POST['task_id'] : 0;

if ($task_id <= 0) {
    http_response_code(400);
    echo 'Invalid task ID';
    exit;
}

// Update the task as complete
$sql = "UPDATE tasks SET is_complete = 1 WHERE task_id = ? AND ID = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo 'Error preparing task update statement: ' . $conn->error;
    exit;
}

$stmt->bind_param('ii', $task_id, $user_id);

if (!$stmt->execute()) {
    echo 'Error updating task: ' . $stmt->error;
    $stmt->close();
    $conn->close();
    exit;
}

// Fetch XP for the task
$stmt->close();
$sql = "SELECT xp FROM tasks WHERE task_id = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo 'Error preparing XP fetch statement: ' . $conn->error;
    $conn->close();
    exit;
}

$stmt->bind_param('i', $task_id);
$stmt->execute();
$result = $stmt->get_result();
$task = $result->fetch_assoc();

if (!$task) {
    echo 'Task not found or XP not available';
    $stmt->close();
    $conn->close();
    exit;
}

$xp = (int)$task['xp'];

// Update the user's XP
$stmt->close();
$sql = "UPDATE users SET xp = xp + ? WHERE ID = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo 'Error preparing user XP update statement: ' . $conn->error;
    $conn->close();
    exit;
}

$stmt->bind_param('ii', $xp, $user_id);
if (!$stmt->execute()) {
    echo 'Error updating user XP: ' . $stmt->error;
} else {
    echo 'Task updated successfully and XP added';
}

}

$stmt->close();
$conn->close();
?>

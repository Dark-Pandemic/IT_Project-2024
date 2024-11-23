<?php
session_start();

if (!isset($_SESSION['ID'])) {
    http_response_code(401);
    echo 'User not logged in';
    exit;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mentalhealthapp";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['ID'];
$task_id = isset($_POST['task_id']) ? (int)$_POST['task_id'] : 0;

if ($task_id <= 0) {
    http_response_code(400);
    echo 'Invalid task ID';
    exit;
}

$conn->begin_transaction();

try {
    // Ensure task is not already completed
    $stmt = $conn->prepare("SELECT is_complete, xp_points FROM tasks WHERE task_id = ? AND user_id = ?");
    $stmt->bind_param('ii', $task_id, $user_id);
    $stmt->execute();
    $task = $stmt->get_result()->fetch_assoc();

    if (!$task) {
        throw new Exception('Task not found.');
    }

    if ($task['is_complete']) {
        throw new Exception('Task is already completed.');
    }

    $xp = $task['xp_points'];
    $stmt->close();

    // Update the task as complete
    $stmt = $conn->prepare("UPDATE tasks SET is_complete = 1 WHERE task_id = ? AND user_id = ?");
    $stmt->bind_param('ii', $task_id, $user_id);
    if (!$stmt->execute()) {
        throw new Exception('Failed to update task.');
    }
    $stmt->close();

    // Update user XP
    $stmt = $conn->prepare("UPDATE userloginreg SET points = points + ? WHERE user_id = ?");
    $stmt->bind_param('ii', $xp, $user_id);
    if (!$stmt->execute()) {
        throw new Exception('Failed to update user XP.');
    }

    $conn->commit();
    echo 'Task updated successfully and XP added.';
} catch (Exception $e) {
    $conn->rollback();
    http_response_code(500);
    echo 'Error: ' . $e->getMessage();
} finally {
    $conn->close();
}

?>

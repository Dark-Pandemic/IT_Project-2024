<?php
session_start();

if (!isset($_SESSION['username'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

$user_id = $_SESSION['ID'];
$task_id = isset($_POST['task_id']) ? (int)$_POST['task_id'] : 0;

if ($task_id <= 0) {
    echo json_encode(['error' => 'Invalid task ID']);
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

$conn->begin_transaction();

try {
    // Fetch the task's XP points
    $stmt = $conn->prepare("SELECT xp_points FROM tasks WHERE ID = ? AND ID = ?");
    $stmt->bind_param("ii", $task_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $task = $result->fetch_assoc();
    
    if (!$task) {
        throw new Exception('Task not found');
    }
    
    $xp = $task['xp_points'];

    // Update the task as complete
    $stmt = $conn->prepare("UPDATE tasks SET is_complete = 1 WHERE ID = ? AND ID = ?");
    $stmt->bind_param("ii", $task_id, $user_id);
    if (!$stmt->execute()) {
        throw new Exception('Failed to update task');
    }

    // Update user's XP
    $stmt = $conn->prepare("UPDATE userloginreg SET points = points + ? WHERE ID = ?");
    $stmt->bind_param("ii", $xp, $user_id);
    if (!$stmt->execute()) {
        throw new Exception('Failed to update user XP');
    }

    $conn->commit();
    echo json_encode(['status' => 'Task updated successfully']);
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['error' => $e->getMessage()]);
} finally {
    $stmt->close();
    $conn->close();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

?>

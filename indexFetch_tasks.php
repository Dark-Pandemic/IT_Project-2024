<?php
$host = 'localhost';
$dbname = 'mentalhealthapp';
$username = 'root';
$password = ''; // Use your XAMPP password if set

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch all tasks from the table
    $stmt = $pdo->query("SELECT task_id, task_name, task_type, task_description FROM tasks");
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Output tasks as JSON
    echo json_encode($tasks);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]);
}

?>

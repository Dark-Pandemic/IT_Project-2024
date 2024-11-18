<?php
session_start(); // Start the session
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the user is logged in and has a valid user ID in the session
    if (isset($_SESSION['userId'])) {
        $userId = $_SESSION['userId']; // Get the user ID from the session
    } else {
        echo "<p class='error'>Error: You must be logged in to submit an entry.</p>";
        exit;
    }
        

    $file_name = $_POST['file_name'];
    $file_content = $_POST['file_content'];

    $sql = "INSERT INTO journal (userId, file_name, file_content) VALUES (:userId, :file_name, :file_content)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':userId', $userId);
    $stmt->bindParam(':file_name', $file_name);
    $stmt->bindParam(':file_content', $file_content);
    
    if ($stmt->execute()) {
        echo "<p class='success'>Entry saved successfully!</p>";
    } else {
        echo "<p class='error'>Error saving entry. Please try again.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Write a New Journal Entry</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-image: url('background1.jpg');
            background-size: cover;
            background-position: center center;
            background-attachment: fixed;
            color: #333;
            margin: 0;
            padding: 0;
        }
        header {
            text-align: center;
            padding: 30px 20px;
            background-color: rgba(0, 0, 0, 0.5);
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            width: 80%;
            margin: 50px auto;
        }
        h1 {
            color: #a7c7e7;
            font-size: 40px;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin: 0;
        }
        .form-container {
            width: 80%;
            margin: 0 auto;
            text-align: center;
            background-color: rgba(255, 255, 255, 0.8);
            padding: 30px;
            border-radius: 10px;
        }
        label {
            font-size: 18px;
            margin-top: 20px;
            display: block;
        }
        textarea {
            width: 100%;
            height: 300px;
            padding: 15px;
            font-size: 18px;
            border: 1px solid #ccc;
            border-radius: 8px;
            resize: none;
            background-color: #f9f9f9;
            color: #333;
        }
        button {
            background-color: #a7c7e7;
            color: white;
            padding: 15px 30px;
            font-size: 18px;
            text-decoration: none;
            border-radius: 8px;
            margin-top: 20px;
            transition: transform 0.2s ease, background-color 0.3s;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        button:hover {
            background-color: #85aad6;
            transform: translateY(-4px);
        }
        .back-home-btn {
            background-color: #f4e1d2;
            color: #333;
            padding: 15px 30px;
            font-size: 18px;
            text-decoration: none;
            border-radius: 8px;
            margin-top: 20px;
            display: inline-block;
            transition: transform 0.2s ease, background-color 0.3s;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .back-home-btn:hover {
            background-color: #e1c1a0;
            transform: translateY(-4px);
        }
    </style>
</head>
<body>

    <header>
        <h1>Write a New Journal Entry</h1>
    </header>

    <div class="form-container">
        <form action="write_entry.php" method="post">
        <label for="file_name">Title of Your Journal Entry:</label>
        <input type="text" id="file_name" name="file_name" placeholder="Enter a title for your journal entry" required style="width: 100%; padding: 15px; font-size: 18px; border-radius: 8px; border: 1px solid #ccc; background-color: #f9f9f9; box-sizing: border-box;">

        <label for="file_content">Your Journal Entry:</label>
            <textarea id="file_content" name="file_content" placeholder="Write your journal entry here..." required></textarea>

            <button type="submit">Save Entry</button>
        </form>
        <a href="journal.php" class="back-home-btn">Back to Home</a>
    </div>

</body>
</html>

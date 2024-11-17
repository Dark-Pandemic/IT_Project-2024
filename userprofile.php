<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: loginform.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$conn = new mysqli('localhost', 'root', '', 'mentalhealthapp');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    
    $update_query = $conn->prepare("UPDATE userloginreg SET username = ?, email = ? WHERE id = ?");
    $update_query->bind_param("ssi", $username, $email, $user_id);

    if ($update_query->execute()) {
        $success_message = "Profile updated";
    } else {
        $error_message = "Error updating profile: " . $conn->error;
    }
}

$sql = "SELECT username, email FROM userloginreg WHERE id='$user_id'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    session_destroy();
    header("Location: loginform.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            padding: 20px;
        }
        .container {
            max-width: 400px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 30px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
        }
        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 10px;
        }
        button {
            padding: 10px 15px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 30px;
            cursor: pointer;
        }
        button:hover {
            background-color: #218838;
        }
        .logout {
            position: absolute;
            top: 20px;
            right: 20px;
            background-color: #00aaff;
            padding: 10px 15px;
            color: white;
            border-radius: 30px;
            border: none;
            cursor: pointer;
        }
        .logout:hover {
            background-color: #0088cc;
        }
        .message {
            text-align: center;
            margin: 10px 0;
        }
        .success {
            color: green;
        }
        .error {
            color: red;
        }
        a {
            font-size: 13px;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <form method="POST" action="">
        <button type="submit" name="logout" class="logout">Logout</button>
    </form>

    <div class="container">
        <h2>Profile</h2>
        <?php if (isset($success_message)) echo "<div class='message success'>$success_message</div>"; ?>
        <?php if (isset($error_message)) echo "<div class='message error'>$error_message</div>"; ?>
        
        <form method="POST" action="">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>">

            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>">

            <label for="password">Password</label>
            <input type="password" id="password" name="password" value = "<?php echo htmlspecialchars($user['password']); ?>" disabled>
            <a href="changepassword.html">Change Password</a>
            
            <br><br><br>

            <button type="submit" name="update">Update</button>
        </form>
    </div>
</body>
</html>

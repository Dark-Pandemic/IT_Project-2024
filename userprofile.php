<?php
session_start();

$username = $_SESSION['username'];
$user_id = $_SESSION['ID']; // Assuming user_id is stored in the session

// Ensure session variables are set
if (!isset($_SESSION['username']) || !isset($_SESSION['ID'])) {
    die("Error: User is not logged in.");
}

//$username = $_SESSION['username'];
//$user_id = $_SESSION['ID']; // Assuming user_id is stored in the session

$conn = new mysqli('localhost', 'root', '', 'mentalhealthapp');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize user array with default values to avoid null errors
$user = ['username' => '', 'email' => '', 'profile_pic' => 'uploads/default-profile.png'];

// Fetch user details
$sql = "SELECT username, email, profile_pic FROM userloginreg WHERE ID = ?";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user) {
        $user = ['username' => '', 'email' => '', 'profile_pic' => 'uploads/default-profile.png'];
        $error_message = "No user data found for ID: " . htmlspecialchars($user_id);
        error_log("No user data found for ID: " . $user_id);
    }
} else {
    $error_message = "Database query failed: " . $conn->error;
    error_log("SQL Error: " . $conn->error);
}
  

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_details'])) {
        // Update username and email
        $username = $conn->real_escape_string($_POST['username']);
        $email = $conn->real_escape_string($_POST['email']);

        $update_query = $conn->prepare("UPDATE userloginreg SET username = ?, email = ? WHERE ID = ?");
        $update_query->bind_param("ssi", $username, $email, $user_id);

        if ($update_query->execute()) {
            if ($update_query->affected_rows > 0) {
                $success_message = "Details updated successfully.";
            } else {
                $error_message = "No changes were made to the details.";
            }
        } else {
            $error_message = "Error updating details: " . $conn->error;
            error_log("Update details error: " . $conn->error);
        }
    } elseif (isset($_POST['update_photo']) && isset($_FILES['profile_pic'])) {
        // Handle profile picture upload
        $target_dir = "uploads/";
        $image_name = basename($_FILES["profile_pic"]["name"]);
        $target_file = $target_dir . uniqid() . "_" . $image_name;
        $upload_ok = 1;
        $image_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if file is an image
        $check = getimagesize($_FILES["profile_pic"]["tmp_name"]);
        if ($check === false) {
            $error_message = "File is not an image.";
            $upload_ok = 0;
        }

        // Allow only certain file formats
        if (!in_array($image_file_type, ['jpg', 'png', 'jpeg', 'gif'])) {
            $error_message = "Only JPG, JPEG, PNG & GIF files are allowed.";
            $upload_ok = 0;
        }

        if ($upload_ok == 1) {
            if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_file)) {
                // Save the image path in the database
                $update_query = $conn->prepare("UPDATE userloginreg SET profile_pic = ? WHERE ID = ?");
                $update_query->bind_param("si", $target_file, $user_id);

                if ($update_query->execute()) {
                    if ($update_query->affected_rows > 0) {
                        $success_message = "Profile picture updated successfully.";
                    } else {
                        $error_message = "No changes were made to the profile picture.";
                    }
                } else {
                    $error_message = "Error updating profile picture: " . $conn->error;
                    error_log("Update profile picture error: " . $conn->error);
                }
            } else {
                $error_message = "Error uploading file.";
                error_log("File upload error for file: " . $_FILES["profile_pic"]["name"]);
            }
        }
    }
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
            max-width: 500px;
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
        input[type="text"], input[type="email"], input[type="password"], input[type="file"] {
            width: 95%;
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
        .profile-photo img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            display: block;
            margin: 10px auto;
        }

    </style>
</head>
<body>
    <div class="container">
        <h2>Profile</h2>
        <div class="profile-photo">
            <!-- Display the profile picture -->
            <?php
                if (!empty($user['profile_pic']) && file_exists($user['profile_pic'])) {
                    echo '<img src="' . htmlspecialchars($user['profile_pic']) . '" alt="Profile Picture" />';
                } else {
                    echo '<img src="uploads/default-profile.png" alt="Profile Picture" />';
                }
            ?>
        </div>

        <!-- Success/Error Messages -->
        <?php if (isset($success_message)) echo "<div class='message success'>$success_message</div>"; ?>
        <?php if (isset($error_message)) echo "<div class='message error'>$error_message</div>"; ?>

        <!-- Update Details Form -->
        <form method="POST" action="">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

            <button type="submit" name="update_details">Update Details</button>
        </form>

        <!-- Update Profile Picture Form -->
        <form method="POST" enctype="multipart/form-data" action="">
            <label for="profile_pic">Profile Picture</label>
            <input type="file" id="profile_pic" name="profile_pic" accept="image/*" required>

            <button type="submit" name="update_photo">Update Profile Picture</button>
        </form>
    </div>
</body>
</html>

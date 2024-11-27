<?php
session_start();
/*
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username']; // Use session if available
} elseif (isset($_COOKIE['username'])) {
    $username = $_COOKIE['username']; // Use cookie if session doesn't exist
} else {
    $username = "Guest"; // Fallback for anonymous access
}
*/

if (isset($_SESSION['ID'])) {
    $user_id = $_SESSION['ID']; // Use session if available
  } elseif (isset($_COOKIE['ID'])) {
    $user_id = $_COOKIE['ID']; // Use cookie if session doesn't exist
  } else {
    $user_id = 0; // Fallback for anonymous access
  }
  

// Database connection
$conn = new mysqli('localhost', 'root', '', 'mentalhealthapp');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize default profile picture
$default_pic = 'uploads/default-profile.png';

// Retrieve user profile picture
$sql = "SELECT profile_pic FROM userloginreg WHERE ID = ?";
$stmt = $conn->prepare($sql);
if ($stmt) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $profile_pic = $user && $user['profile_pic'] ? $user['profile_pic'] : $default_pic;
} else {
    $profile_pic = $default_pic; // Fallback to default
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
            $success_message = "Details updated successfully.";
        } else {
            $error_message = "Error updating details: " . $conn->error;
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
                    $success_message = "Profile picture updated successfully.";
                    $profile_pic = $target_file; // Update current session
                } else {
                    $error_message = "Error updating profile picture: " . $conn->error;
                }
            } else {
                $error_message = "Error uploading file.";
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
	<link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: Poppins, sans-serif;
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
            position: relative;
        }
        .profile-photo-circle {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #28a745;
            position: absolute;
            top: -50px;
            left: calc(50% - 50px);
            background-color: #fff;
        }
        h2 {
            text-align: center;
            color: #333;
            margin-top: 60px;
        }
        label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
        }
        input[type="text"], input[type="email"], input[type="file"] {
            width: 95%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 10px;
        }
        button {
            padding: 10px 15px;
            background-color: #00aaff;
            color: white;
            border: none;
            border-radius: 30px;
            cursor: pointer;
        }
        button:hover {
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
    </style>
</head>
<body>

	<header>
    <nav class="navbar">
        <button class="menu-toggle">☰</button>
        <div class="fancy-menu">
            <h1>Dashboard</h1>
            <ul>
				<li><a href="index.php">Home</a></li>
                <li><a href="userprofile.php">Profile</a></li>
                <li><a href="tasks\tasks_1.php">Tasks</a></li>
                <li><a href="journal_final\journal.php">Journal</a></li>
                <li><a href="subscriptions\doctor.html">Subscription</a></li>
                <li><a href="badges\badges.html">Badges</a></li>
                <li><a href="contacts\contacts_index.php">Emergency Contacts</a></li>
            </ul>
        </div>
    </nav>
</header>

    <div class="container">
        <img src="<?php echo htmlspecialchars($profile_pic); ?>" alt="Profile Picture" class="profile-photo-circle">
        <h2>Profile</h2>

        <!-- Success/Error Messages -->
        <?php if (isset($success_message)) echo "<div class='message success'>$success_message</div>"; ?>
        <?php if (isset($error_message)) echo "<div class='message error'>$error_message</div>"; ?>

        <!-- Update Details Form -->
        <form method="POST" action="">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" placeholder="Enter new username" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Enter new email">

            <button type="submit" name="update_details">Update Details</button>
        </form>

        <!-- Update Profile Picture Form -->
        <form method="POST" enctype="multipart/form-data" action="">
            <label for="profile_pic">Profile Picture</label>
            <input type="file" id="profile_pic" name="profile_pic" accept="image/*" required>

            <button type="submit" name="update_photo">Update Profile Picture</button>
        </form>
    </div>
	<script>
		// Get the button and the menu
    const menuToggle = document.querySelector('.menu-toggle');
    const fancyMenu = document.querySelector('.fancy-menu');

    // Toggle the menu display when the button is clicked
    menuToggle.onclick = function() {
        if (fancyMenu.style.display === "block") {
            fancyMenu.style.display = "none";
        } else {
            fancyMenu.style.display = "block";
        }
    };

    // Optional: Close the menu if the user clicks outside of it
    window.onclick = function(event) {
        if (!event.target.matches('.menu-toggle')) {
            if (fancyMenu.style.display === "block") {
                fancyMenu.style.display = "none";
            }
        }
    };
	</script>
</body>
</html>

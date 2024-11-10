<?php 
session_start();

// Database connection
$host = "localhost";
$username = "root";
$password = "";
$databasename = "mentalhealthapp";

$conn = new mysqli($host, $username, $password, $databasename);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize alert message variables
$successAlert = $errorAlert = "";

// Login logic
if (isset($_POST['loginbtn'])) {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Prepare statement to check if the username exists and fetch the hashed password
    $stmt = $conn->prepare("SELECT * FROM userloginreg WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the user exists
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        
        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Set session variables on successful login
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_id'] = $user['ID'];
            
            // Success alert message
            $successAlert = "Login Successful! Redirecting...";
            
            // Redirect to dashboard after 2 seconds (using JavaScript)
            echo "<script>
                    setTimeout(function() {
                        window.location.href = 'userdashboard.php';
                    }, 2000);
                  </script>";
        } else {
            // Error alert message for incorrect password
            $errorAlert = "Incorrect password. Please try again.";
        }
    } else {
        // Error alert message if username does not exist
        $errorAlert = "Username does not exist.";
    }
    $stmt->close();
}

// Password reset logic
if (isset($_POST['resetbtn'])) {
    $email = $_POST['userEmailOrPhone'];
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];

    // Check if new passwords match
    if ($newPassword === $confirmPassword) {
        // Prepare statement to check if the email exists
        $stmt = $conn->prepare("SELECT * FROM userloginreg WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        // If email exists, update the password
        if ($result->num_rows == 1) {
            $hashed_password = password_hash($newPassword, PASSWORD_DEFAULT);
            $updateStmt = $conn->prepare("UPDATE userloginreg SET password = ? WHERE email = ?");
            $updateStmt->bind_param("ss", $hashed_password, $email);

            if ($updateStmt->execute()) {
                $successAlert = "Password reset successfully!";
            } else {
                $errorAlert = "Failed to reset password.";
            }
            $updateStmt->close();
        } else {
            $errorAlert = "Email not found.";
        }
        $stmt->close();
    } else {
        $errorAlert = "Passwords do not match.";
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: #A8B5E0;
        }
        .login-container {
            background-color: #e7e7e7;
            width: 350px;
            border-radius: 70px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
        }
        .login-container h1 { color: black; }
        input[type="text"], input[type="password"] {
            width: 80%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 50px;
            box-sizing: border-box;
        }
        .remember-me {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 10px 0;
            font-size: 12px;
        }
        input[type="submit"] {
            width: 85%;
            background-color: #00aaff;
            color: white;
            padding: 12px 20px;
            margin: 15px 0;
            border: none;
            border-radius: 50px;
            cursor: pointer;
        }
        input[type="submit"]:hover { background-color: #0088cc; }
        .forgot-password {
            font-size: 12px;
            margin-top: 5px;
        }
        .terms {
            font-size: 12px;
        }

        /* Alert Block */
        #successAlert, #errorAlert {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            padding: 20px;
            border-radius: 10px;
            font-size: 18px;
            z-index: 1000;
            width: 300px;
            text-align: center;
        }

        #successAlert {
            background-color: green;
            color: white;
        }

        #errorAlert {
            background-color: red;
            color: white;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 300px;
            border-radius: 30px;
            text-align: center;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 24px;
            font-weight: bold;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <!-- Success Alert Block -->
    <div id="successAlert"><?php echo $successAlert; ?></div>

    <!-- Error Alert Block -->
    <div id="errorAlert"><?php echo $errorAlert; ?></div>

    <div class="login-container">
        <h1>Log In</h1>
        <form name="loginForm" method="POST" action="loginform.php">
            <input type="text" id="username" name="username" placeholder="Username" required>
            <input type="password" id="password" name="password" placeholder="Password" required>
            <div class="remember-me">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Remember me</label>
            </div>
            <input type="submit" name="loginbtn" value="Log In">
        </form>
        <p class="forgot-password"><a href="#" onclick="openForgotPasswordModal()">Forgot password?</a></p>
        <p class="terms">Don't have an account? <a href="loginreg.php">Sign Up</a></p>
    </div>

    <!-- Forgot Password Modal -->
    <div id="forgotPasswordModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeForgotPasswordModal()">&times;</span>
            <h2>Verify Identity</h2>
            <p>Please enter your registered email or phone number to reset your password.</p>
            <input type="email" id="userEmailOrPhone" name="userEmailOrPhone" placeholder="Email or Phone" required>
            <button onclick="verifyUser()">Submit</button>
        </div>
    </div>

    <!-- Reset Password Modal -->
    <div id="resetPasswordModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeResetPasswordModal()">&times;</span>
            <h2>Reset Password</h2>
            <form onsubmit="return resetPassword()">
                <input type="password" id="newPassword" name="newPassword" placeholder="New Password" required>
                <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm Password" required>
                <input type="submit" value="Reset">
            </form>
        </div>
    </div>

    <script>
        // Display success or error alert based on login result
        if (document.getElementById("successAlert").innerText) {
            document.getElementById("successAlert").style.display = "block";
            setTimeout(function() {
                document.getElementById("successAlert").style.display = "none";  // Hide after 5 seconds
            }, 5000); // 5000ms = 5 seconds
        }

        if (document.getElementById("errorAlert").innerText) {
            document.getElementById("errorAlert").style.display = "block";
            setTimeout(function() {
                document.getElementById("errorAlert").style.display = "none";  // Hide after 5 seconds
            }, 3000); 
        }

        // Clears the inputs on page load
        window.onload = function() {
            document.getElementById("username").value = "";
            document.getElementById("password").value = "";
        };

        // Function to open and close modals
        function openForgotPasswordModal() {
            document.getElementById("forgotPasswordModal").style.display = "flex";
        }
        
        function closeForgotPasswordModal() {
            document.getElementById("forgotPasswordModal").style.display = "none";
        }

        function openResetPasswordModal() {
            document.getElementById("resetPasswordModal").style.display = "flex";
        }

        function closeResetPasswordModal() {
            document.getElementById("resetPasswordModal").style.display = "none";
        }

        function verifyUser() {
            alert("Verification process initiated");
            closeForgotPasswordModal();
            document.getElementById("resetPasswordModal").style.display = "flex";
        }

        function resetPassword() {
            var password = document.getElementById("newPassword").value;
            var confirmPassword = document.getElementById("confirmPassword").value;

            if (password !== confirmPassword) {
                alert("Passwords do not match.");
                return false;
            }

            alert("Password reset successfully.");
            closeResetPasswordModal();
            return false;
        }
    </script>
</body>
</html>

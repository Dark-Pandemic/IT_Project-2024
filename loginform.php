<?php
// Include PHPMailer
require 'C:\xampp\htdocs\IT_Project-2024\emailreset\PHPMailer-master\src\PHPMailer.php';
require 'C:\xampp\htdocs\IT_Project-2024\emailreset\PHPMailer-master\src\SMTP.php';
require 'C:\xampp\htdocs\IT_Project-2024\emailreset\PHPMailer-master\src\Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Database connection
$conn = new mysqli('localhost', 'root', '', 'mentalhealthapp');
date_default_timezone_set('Africa/Johannesburg');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$error = ""; // To store error message

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check credentials in the database
    $stmt = $conn->prepare("SELECT * FROM userloginreg WHERE username = ? AND password = ?");
    $stmt->bind_param('ss', $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Login successful, redirect to loading page
        header("Location: loadingpage.php");
        exit;
    } else {
        // Incorrect credentials
        $error = "Invalid username or password!";
    }
}


// Handle forgot password request
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reset_password'])) {
    $email = $_POST['email'];

    // Check if the email exists in the database
    $stmt = $conn->prepare("SELECT * FROM userloginreg WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Generate a new token
        $token = bin2hex(random_bytes(32));

        // Set expiration time 15 minutes from now
        $expires = date('Y-m-d H:i:s', strtotime('+15 minutes'));

        // Save the token and expiration time in the database
        $stmt = $conn->prepare("UPDATE userloginreg SET reset_token = ?, reset_expires = ? WHERE email = ?");
        $stmt->bind_param('sss', $token, $expires, $email);
        $stmt->execute();

        // Generate reset link
        $resetLink = "http://localhost/IT_Project-2024/loginresetpassword.php?token=" . $token;


        // Send the email using PHPMailer
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'moodifysa@gmail.com'; // Your Gmail
            $mail->Password = 'ffvl fgwa phqi qekp';  // App Password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('moodifysa@hmail.com', 'moodify');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';
            $mail->Body = "Hi, click <a href='$resetLink'>here</a> to reset your password. This link is valid for 15 minutes.";

            $mail->send();
            $resetMessage = "A reset link has been sent to your email.";
        } catch (Exception $e) {
            $resetMessage = "Email could not be sent. Error: {$mail->ErrorInfo}";
        }
    } else {
        $resetMessage = "No account found with that email.";
    }
}

// Handle password reset via token
if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Validate the token
    $stmt = $conn->prepare("SELECT * FROM userloginreg WHERE reset_token = ? AND reset_expires > NOW()");
    $stmt->bind_param('s', $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Token is valid and not expired
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $new_password = $_POST['password'];

            // Update the password and remove the reset token and expiration time
            $stmt = $conn->prepare("UPDATE userloginreg SET password = ?, reset_token = NULL, reset_expires = NULL WHERE reset_token = ?");
            $stmt->bind_param('ss', $new_password, $token);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                $passwordResetMessage = "Your password has been successfully reset!";
            } else {
                $passwordResetMessage = "There was an issue updating your password. Please try again.";
            }
        }
    } else {
        // Invalid or expired token
        $passwordResetMessage = "Invalid or expired token.";
    }
}

?>

<!DOCTYPE html>
<html>
<head>
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

        .login-container h1 {
            color: black;
        }

        input[type="text"], input[type="password"] {
            width: 80%;
            padding: 12px;
            margin: 10px 0;
            display: inline-block;
            border: 1px solid #ccc;
            border-radius: 50px;
            box-sizing: border-box;
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

        input[type="submit"]:hover {
            background-color: #0088cc;
        }

        .alert-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            background: rgba(0, 0, 0, 0.5);
            display: none;
        }

        .alert-box {
            background-color: #e7ffe7;
            color: #007700;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            font-size: 18px;
            text-align: center;
        }

        .alert-box.alert-error {
            background-color: #ffe7e7;
            color: #ff0000;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: #fefefe;
            padding: 20px;
            border-radius: 10px;
            width: 300px;
            text-align: center;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 20px;
            cursor: pointer;
        }

         /* Error alert styles */
         .error-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 999;
        }

        .error-box {
            background-color: #ffcccc;
            color: #cc0000;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            font-size: 18px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }



    </style>
</head>
<body>

<div class="login-container">
    <h1>Log In</h1>
    <form action="loginform.php" method="POST">
        <input type="text" id="username" name="username" placeholder="Username" required>
        <input type="password" id="password" name="password" placeholder="Password" required>
        <div class="remember-me">
            <input type="checkbox" id="remember" name="remember">
            <label for="remember">Remember me</label>
        </div>
        <input type="submit" value="Log In">
    </form>
    <p class="forgot-password"><a href="#" id="forgotPasswordLink">Forgot password?</a></p>
    <p class="terms">Don't have an account? <a href="signupform.php">Sign up here!</a></p>
</div>

<!-- Forgot Password Modal -->
<div id="forgotPasswordModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2>Reset Password</h2>
        <form action="loginform.php" method="POST">
            <input type="email" name="email" placeholder="Enter your email" required>
            <button type="submit" name="reset_password">Send Reset Link</button>
        </form>
        <?php if (isset($resetMessage)) { echo "<p>$resetMessage</p>"; } ?>
    </div>
</div>

<?php if (isset($passwordResetMessage)) { echo "<div class='alert-box'>$passwordResetMessage</div>"; } ?>

<script>
    const modal = document.getElementById('forgotPasswordModal');
    const forgotPasswordLink = document.getElementById('forgotPasswordLink');

    // Open modal when "Forgot password?" is clicked
    forgotPasswordLink.onclick = function() {
        modal.style.display = 'flex';
    }

    // Close modal when "x" is clicked
    function closeModal() {
        modal.style.display = 'none';
    }

    // Close modal if clicked outside of modal content
    window.onclick = function(event) {
        if (event.target === modal) {
            closeModal();
        }
    }
</script>

<!-- Error Alert Box -->
<?php if (!empty($error)) : ?>
    <div class="error-overlay" id="errorOverlay">
        <div class="error-box">
            <?php echo $error; ?>
        </div>
    </div>
    <script>
        // Remove the error overlay after 4 seconds
        setTimeout(() => {
            document.getElementById('errorOverlay').style.display = 'none';
            document.getElementById('password').value = ''; // Clear password field
        }, 4000);
    </script>
<?php endif; ?>

</body>
</html>

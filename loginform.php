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
</div>

<div id="forgotPasswordModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2>Reset Password</h2>
        <form id="resetPasswordForm" action="loginform.php" method="POST">
            <input type="text" name="reset_username" placeholder="Username" required>
            <input type="email" name="reset_email" placeholder="Email" required>
            <input type="password" name="new_password" placeholder="New Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            <input type="submit" value="Reset Password">
        </form>
    </div>
</div>

<div id="alertOverlay" class="alert-overlay">
    <div id="alertBox" class="alert-box"></div>
</div>

<?php
// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mentalhealthapp";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle login request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $sql = "SELECT * FROM userloginreg WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<script>
                 // Create a dimming overlay
            const overlay = document.createElement('div');
            overlay.style.position = 'fixed';
            overlay.style.top = 0;
            overlay.style.left = 0;
            overlay.style.width = '100%';
            overlay.style.height = '100%';
            overlay.style.backgroundColor = 'rgba(0, 0, 0, 0.5)'; // 50% black for dim effect
            overlay.style.zIndex = '9999'; // Ensure it appears above other content

            // Append the overlay to the body
            document.body.appendChild(overlay);

            // Redirect after 2 seconds
            setTimeout(function() {
                window.location.href = 'loadingpage.php';
            }, 1000);
              </script>";
    } else {
        echo "<script>
                document.getElementById('alertBox').textContent = 'Invalid username or password.';
                document.getElementById('alertBox').classList.add('alert-error');
                document.getElementById('alertOverlay').style.display = 'flex';
                setTimeout(function() { document.getElementById('alertOverlay').style.display = 'none'; }, 2000);
              </script>";
    }
}

// Handle password reset request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reset_username']) && isset($_POST['reset_email'])) {
    $reset_username = $_POST['reset_username'];
    $reset_email = $_POST['reset_email'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== $confirm_password) {
        echo "<script>
                document.getElementById('alertBox').textContent = 'Passwords do not match.';
                document.getElementById('alertBox').classList.add('alert-error');
                document.getElementById('alertOverlay').style.display = 'flex';
                setTimeout(function() { document.getElementById('alertOverlay').style.display = 'none'; }, 2000);
              </script>";
    } else {
        $sql = "SELECT * FROM userloginreg WHERE username = '$reset_username' AND email = '$reset_email'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $update_sql = "UPDATE userloginreg SET password = '$new_password' WHERE username = '$reset_username'";
            if ($conn->query($update_sql) === TRUE) {
                echo "<script>
                        document.getElementById('alertBox').textContent = 'Password reset successful! Please log in with your new password.';
                        document.getElementById('alertBox').classList.remove('alert-error');
                        document.getElementById('alertOverlay').style.display = 'flex';
                        setTimeout(function() {
                            document.getElementById('alertOverlay').style.display = 'none';
                            document.getElementById('forgotPasswordModal').style.display = 'none';
                        }, 3000);
                      </script>";
            } else {
                echo "<script>
                        document.getElementById('alertBox').textContent = 'Error resetting password.';
                        document.getElementById('alertBox').classList.add('alert-error');
                        document.getElementById('alertOverlay').style.display = 'flex';
                        setTimeout(function() { document.getElementById('alertOverlay').style.display = 'none'; }, 2000);
                      </script>";
            }
        } else {
            echo "<script>
                    document.getElementById('alertBox').textContent = 'Invalid username or email.';
                    document.getElementById('alertBox').classList.add('alert-error');
                    document.getElementById('alertOverlay').style.display = 'flex';
                    setTimeout(function() { document.getElementById('alertOverlay').style.display = 'none'; }, 2000);
                  </script>";
        }
    }
}

$conn->close();
?>

<script>
    const modal = document.getElementById('forgotPasswordModal');
    const forgotPasswordLink = document.getElementById('forgotPasswordLink');

    forgotPasswordLink.onclick = function() {
        modal.style.display = "flex";
    }

    function closeModal() {
        modal.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
</script>

</body>
</html>

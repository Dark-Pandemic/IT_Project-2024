<?php
$host = "localhost";
$username = "root";
$password = "";
$databasename = "mentalhealthapp";

// Create connection
$conn = new mysqli($host, $username, $password, $databasename);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$alertMessage = "";
$alertClass = "alert-danger";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $contact = $_POST["contact"];
    $username = $_POST["username"];
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirm-password"];
    $recaptchaResponse = $_POST['g-recaptcha-response'];

    // Verify reCAPTCHA
    $recaptchaSecret = '6LdOTIEqAAAAAFVumLsh73cqPDHmfBbMgPRa3Irx'; // Replace with your secret key
    $recaptchaVerifyUrl = 'https://www.google.com/recaptcha/api/siteverify';
    $response = file_get_contents($recaptchaVerifyUrl.'?secret='.$recaptchaSecret.'&response='.$recaptchaResponse);
    $responseKeys = json_decode($response, true);

    if(intval($responseKeys["success"]) !== 1) {
        $alertMessage = "Please verify that you are not a robot.";
    } else {
        // Password match check
        if ($password !== $confirmPassword) {
            $alertMessage = "Your passwords do not match. Please try again.";
        } else {
            // Check if username or email already exists
            $stmt = $conn->prepare("SELECT * FROM userloginreg WHERE username = ? OR email = ?");
            $stmt->bind_param("ss", $username, $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $alertMessage = "The username or email already exists. Please choose a different one.";
            } else {
                // Hash the password before storing
                //$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                // Insert new user into database
                $stmt = $conn->prepare("INSERT INTO userloginreg (name, email, contact, username, password) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("sssss", $name, $email, $contact, $username, $password); // $hashedPassword

                if ($stmt->execute()) {
                    $alertMessage = "Registration successful! Redirecting to login page...";
                    $alertClass = "alert-success";
                    echo "<script>
                            setTimeout(function() {
                                window.location.href = 'loginform.php';
                            }, 3000);
                          </script>";
                } else {
                    $alertMessage = "An error occurred. Please try again later.";
                }
            }
            $stmt->close();
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 130vh;
            background: linear-gradient(135deg, #A8B5E0, #B6DA9F, #ffc87a);
        }
        .signup-container {
            background-color: #e7e7e7;
            width: 400px;
            border-radius: 70px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
        }
        .signup-container h1 {
            color: black;
        }
        input[type="text"], input[type="email"], input[type="password"], input[type="tel"] {
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
        .terms {
            font-size: 12px;
            margin-top: 10px;
        }

        .alert {
            color: white;
            padding: 20px;
            text-align: center;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 9999;
            display: none;
            transition: opacity 0.5s ease;
        }
        .alert-danger {
            background-color: #f44336;
        }
        .alert-success {
            background-color: #4CAF50;
        }

        .strength-bar {
            height: 5px;
            width: 100%;
            background-color: #ddd;
            margin-top: 5px;
        }

        .strength-bar div {
            height: 100%;
            width: 0;
            background-color: red;
        }

        .password-requirements {
            font-size: 12px;
            margin-top: 5px;
            text-align: left;
        }

        .requirement {
            color: red;
            font-size: 12px;
        }

        .requirement.satisfied {
            color: green;
        }

        #strength-label {
            font-size: 10px;
            margin-top: 5px;
        }
    </style>

    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <script type="text/javascript">
        window.onload = function() {
            var alertMessage = "<?php echo $alertMessage; ?>";
            var alertClass = "<?php echo $alertClass; ?>";

            if (alertMessage !== "") {
                var alertBox = document.getElementById("alert-message");
                alertBox.innerHTML = alertMessage;
                alertBox.className = "alert " + alertClass;
                alertBox.style.display = "block";

                setTimeout(function() {
                    alertBox.style.display = "none";
                }, 10000);
            }
        }

        function updateStrengthBar(password) {
            var strengthBar = document.getElementById('strength-bar');
            var strength = 0;
            var strengthLabel = document.getElementById('strength-label');

            var requirements = document.querySelectorAll('.requirement');

            // Reset requirement colors
            requirements.forEach(function(requirement) {
                requirement.classList.remove('satisfied');
            });

            // Check password strength
            if (password.length >= 8) {
                strength += 20;
                document.getElementById('length').classList.add('satisfied');
            }
            if (/[A-Z]/.test(password)) {
                strength += 20;
                document.getElementById('uppercase').classList.add('satisfied');
            }
            if (/[a-z]/.test(password)) {
                strength += 20;
                document.getElementById('lowercase').classList.add('satisfied');
            }
            if (/[0-9]/.test(password)) {
                strength += 20;
                document.getElementById('number').classList.add('satisfied');
            }
            if (/[^A-Za-z0-9]/.test(password)) {
                strength += 20;
                document.getElementById('special').classList.add('satisfied');
            }

            // Set strength bar width and color
            strengthBar.style.width = strength + '%';

            if (strength <= 20) {
                strengthBar.style.backgroundColor = 'red';
                strengthLabel.textContent = "Weak";
            } else if (strength <= 60) {
                strengthBar.style.backgroundColor = 'yellow';
                strengthLabel.textContent = "Moderate";
            } else if (strength <= 80) {
                strengthBar.style.backgroundColor = 'orange';
                strengthLabel.textContent = "Strong";
            } else {
                strengthBar.style.backgroundColor = 'green';
                strengthLabel.textContent = "Very Strong";
            }
        }

        function validateForm() {
            var password = document.forms["signupForm"]["password"].value;
            var confirmPassword = document.forms["signupForm"]["confirm-password"].value;
            var terms = document.forms["signupForm"]["terms"].checked;

            if (password.length < 8) {
                alert("Password must be at least 8 characters.");
                return false;
            }

            if (password !== confirmPassword) {
                alert("Passwords do not match.");
                return false;
            }

            if (!terms) {
                alert("You must accept the terms and conditions.");
                return false;
            }

            return true;
        }
    </script>
</head>
<body>
    <div class="alert" id="alert-message"></div>

    <div class="signup-container">
        <h1>Sign Up</h1>
        <form action="" method="POST" name="signupForm" onsubmit="return validateForm();">
            <input type="text" id="name" name="name" placeholder="Enter your Full Name" required>
            <input type="email" id="email" name="email" placeholder="Enter your Email" required>
            <input type="tel" id="contact" name="contact" placeholder="Enter your Contact Number" required>
            <input type="text" id="username" name="username" placeholder="Enter your Username" required>
            <input type="password" id="password" name="password" placeholder="Enter your Password" required oninput="updateStrengthBar(this.value)">

            <div class="strength-bar">
                <div id="strength-bar"></div>
            </div>
            <div id="strength-label">Weak</div>

            <div class="password-requirements">
                <div id="length" class="requirement">At least 8 characters</div>
                <div id="uppercase" class="requirement">At least one uppercase letter</div>
                <div id="lowercase" class="requirement">At least one lowercase letter</div>
                <div id="number" class="requirement">At least one number</div>
                <div id="special" class="requirement">At least one special character</div>
            </div>

            <input type="password" id="confirm-password" name="confirm-password" placeholder="Confirm your Password" required>
            <center><div class="g-recaptcha" data-sitekey="6LdOTIEqAAAAANrpiv9_zptOasGD_gL0bPuBItRq"></div></center>
            <br>
            <input type="checkbox" id="terms" name="terms" required> I accept the <a href="Terms%20and%20Conditions.pdf" target="_blank">terms and conditions</a>
            <br>
            <input type="submit" value="Sign Up">
            <p class="terms">Already have an account? <a href="loginform.php">Log in here!</a></p>
        </form>
    </div>
</body>
</html>

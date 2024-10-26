<?php

$host = "localhost";
$username = "root";
$password = "";
$databasename = "mentalhealthapp";

$conn = new mysqli($host, $username, $password, $databasename);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $contact = $_POST["contact"];
    $username = $_POST["username"];
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirm-password"];

    // Check if passwords match
    if ($password !== $confirmPassword) {
        echo "<script>alert('Passwords do not match.');</script>";
    } else {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO userloginreg (name, email, contact, username, password) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $email, $contact, $username, $hashedPassword);



		//////////pop up not working//////////
////////////trying to get a mssage to say sucesful registration, go to login, or redirect to login///////
/////////login page to appear first, and the signup link at the botton seems a better fit//////



        if ($stmt->execute()) {
            // Show modal for successful registration
            echo "<script>document.addEventListener('DOMContentLoaded', function() { document.getElementById('successModal').style.display = 'block'; });</script>";
            exit(); 
        } else {
            echo "<script>alert('Error: " . $stmt->error . "');</script>";
        }

        $stmt->close(); 
    }
}

$conn->close(); 
?>

<html>
<head>
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
            height: 100vh;
            background: linear-gradient(135deg, #A8B5E0, #B6DA9F, #ffc87a);
        }
        .signup-container {
            background-color: #e7e7e7;
            width: 350px;
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
            background-color: rgb(0,0,0); 
            background-color: rgba(0,0,0,0.4); 
            padding-top: 60px; 
        }
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto; 
            padding: 20px;
            border: 1px solid #888;
            width: 80%; 
            text-align: center;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="signup-container">
        <h1>Sign Up</h1>
        <form action="" method="POST" name="signupForm" onsubmit="return validateForm();">
            <input type="text" id="name" name="name" placeholder="Please enter your Full Name" required>
            <input type="email" id="email" name="email" placeholder="Please enter your Email" required>
            <input type="tel" id="contact" name="contact" placeholder="Please enter your Contact Number" required>
            <input type="text" id="username" name="username" placeholder="Please enter your Username" required>
            <input type="password" id="password" name="password" placeholder="Please enter your Password" required>
            <input type="password" id="confirm-password" name="confirm-password" placeholder="Please confirm your Password" required>
            <br>
            <input type="checkbox" id="terms" name="terms" required>I accept the <a href="#">terms and conditions</a>
            <br>
            <input type="submit" value="Sign Up">
        </form>
        <p class="terms">Already have an account? <a href="loginexample.html">Log in</a></p>
    </div>

    <!-- Modal HTML -->
    <div id="successModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="document.getElementById('successModal').style.display='none'">&times;</span>
            <h2>Registration Successful!</h2>
            <p>Please log in to continue.</p>
            <button onclick="window.location.href='loginreg.php'">Go to Login</button>
        </div>
    </div>

    <script>
        // Close the modal when the user clicks outside of it
        window.onclick = function(event) {
            var modal = document.getElementById('successModal');
            if (event.target == modal) {
                modal.style.display = "none";
            }
        };

        function validateForm() {
            var name = document.forms["signupForm"]["name"].value;
            var email = document.forms["signupForm"]["email"].value;
            var contact = document.forms["signupForm"]["contact"].value;
            var username = document.forms["signupForm"]["username"].value;
            var password = document.forms["signupForm"]["password"].value;
            var confirmPassword = document.forms["signupForm"]["confirm-password"].value;
            var terms = document.forms["signupForm"]["terms"].checked;

            if (password.length < 5 || password.length > 18) {
                alert("Password should be between 5 to 18 characters.");
                return false;
            }

            if (password !== confirmPassword) {
                alert("Passwords do not match");
                return false;
            }

            if (!terms) {
                alert("You must accept the terms and conditions");
                return false;
            }

            return true;
        }
    </script>
</body>
</html>

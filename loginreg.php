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
				height: 130vh;
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
		</style>
	</head>
	
	<body>
		<div class="signup-container">
			<h1>Sign Up</h1>
			<form name="signupForm" onsubmit="return validateForm()">
				<input type="text" id="name" name="name" placeholder="Please enter your Full Name">
				<input type="email" id="email" name="email" placeholder="Please enter your Email">
				<input type="tel" id="contact" name="contact" placeholder="Please enter your Contact Number">
				<input type="text" id="username" name="username" placeholder="Please enter your Username">
				<input type="password" id="password" name="password" placeholder="Please enter your Password">
				<input type="password" id="confirm-password" name="confirm-password" placeholder="Please confirm your Password">
				<br>
				<input type="checkbox" id="terms" name="terms">I accept the <a href="#">terms and conditions</a>
				<br>
				<input type="submit" value="Sign Up">
			</form>
			
			<p class="terms">Already have an account? <a href="loginexample.html">Log in</a></p>
			
		</div>
		
		<script>
			function validateForm() {
				var name = document.forms["signupForm"]["name"].value;
				var email = document.forms["signupForm"]["email"].value;
				var contact = document.forms["signupForm"]["contact"].value;
				var username = document.forms["signupForm"]["username"].value;
				var password = document.forms["signupForm"]["password"].value;
				var confirmPassword = document.forms["signupForm"]["confirm-password"].value;
				var terms = document.forms["signupForm"]["terms"].checked;


				// Check if all fields are filled
				if (name == "" || username == "" || email == "" || contact == "" || password == "" || confirmPassword == "") {
					alert("All fields must be filled out");
					return false;
				}

				// Check password length
				if (password.length > 18) {
					alert("Password cannot exceed 18 characters");
					return false;
				}
				
				if (password.length < 5){
					alert("Password is too short");
					return false;
				}

				// Check if passwords match
				if (password !== confirmPassword) {
					alert("Passwords do not match");
					return false;
				}

				// Check if terms and conditions are accepted
				if (!terms) {
					alert("You must accept the terms and conditions");
					return false;
				}

				return true;
			}
		</script>
	</body>
</html>



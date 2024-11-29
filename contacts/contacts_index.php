<?php
session_start();

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username']; // Use session if available
} elseif (isset($_COOKIE['username'])) {
    $username = $_COOKIE['username']; // Use cookie if session doesn't exist
} else {
    $username = "Guest"; // Fallback for anonymous access
}



// Check if the user is logged out, then destroy session and redirect
if (isset($_GET['logout']) && $_GET['logout'] == 'true') {
  session_unset();
  session_destroy();
  setcookie("username", "", time() - 3600, "/"); // Optional: Delete the cookie
  header("Location: loginform.php"); // Redirect to login page
  exit();
}


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Emergency Contacts - Mental Health Awareness</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
	<style>
		/* Basic Styles */
body {
    font-family: Poppins, sans-serif;
    margin: 0;
    padding: 0;
    background: linear-gradient(to bottom, #ffe4e1, #fafad2, #e0ffff, #d8bfd8, #ffe4b5);
    background-size: 100% 200%;
    animation: gradientAnimation 15s ease infinite;
    color: #333;
}

/* Animation for smooth gradient blending */
@keyframes gradientAnimation {
    0% {
        background-position: top;
    }
    50% {
        background-position: bottom;
    }
    100% {
        background-position: top;
    }
}

h2 {
    font-size: 2rem;
}

.contact-card {
    background-color: rgba(255, 255, 255, 0.20);
    color: #0e5066;
    padding: 15px;
    margin: 10px 0;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.contact-card h3 {
    color: #333;
}

.contact-card p {
    font-size: 1.2rem;
    margin: 5px 0;
}

.custom-contact {
    background-color: rgba(255, 255, 255, 0.20);
    color: #0e5066;
    padding: 20px;
    margin: 30px 0;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

form label {
    display: block;
    margin: 10px 0 5px;
}

form input {
    width: 100%;
    padding: 10px;
    margin: 5px 0;
    border: 1px solid #ddd;
    border-radius: 5px;
}

form button {
    background-color: #00aaff;
    color: white;
    padding: 10px 15px;
    border: none;
    border-radius: 30px;
    cursor: pointer;
}

form button:hover {
    background-color: #0088cc;
}

footer {
    background-color: #6CB4EE;
    color: white;
    text-align: center;
    padding: 20px;
    width: 100%;
}

.footer-content {
    max-width: 1200px;
    margin: 0 auto;
}

.social-media {
    margin-bottom: 5px;
}

.social-link {
    margin: 0 8px;
    color: white;
    text-decoration: none;
    transition: color 0.3s ease;
}

.social-link:hover {
    color: grey;
}

.contact-info p {
    margin: 3px 0;
}

.contact-info a {
    color: #333;
    text-decoration: none;
}

.contact-info a:hover {
    text-decoration: underline;
}

/* Header Styles */
header {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 1000;
}

.navbar {
    padding: 10px;
}

.menu-toggle {
    color: black;
    border: none;
    cursor: pointer;
    font-size: 20px;
    border-radius: 7px;
}

.fancy-menu {
    display: none;
    background-color: #6CB4EE;
    position: fixed;
    top: 0;
    left: 0;
    height: 100%;
    width: 220px;
    box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
    z-index: 1;
    border-radius: 15px 0 0 15px;
    padding-top: 20px;
    transition: transform 0.3s ease;
    transform: translateX(-220px);
}

.fancy-menu.show {
    display: block;
    transform: translateX(0);
}

.content {
    transition: margin-left 0.3s ease;
}

.menu-open .content {
    margin-left: 220px;
}

.fancy-menu h1 {
    margin: 0;
    padding: 10px;
    color: white;
    font-size: 1.5em;
    text-align: center;
    border-bottom: 1px solid #555;
    padding-bottom: 10px;
}

.fancy-menu ul {
    list-style-type: none;
    padding: 0;
    margin: 0;
}

.fancy-menu li {
    padding: 10px 20px;
}

.fancy-menu a {
    color: white;
    text-decoration: none;
    display: block;
    text-align: center;
}

.fancy-menu a:hover {
    color: grey;
    transform: translateX(5px);
}

.close-menu {
    background-color: transparent;
    color: white;
    border: none;
    font-size: 20px;
    position: absolute;
    top: 7px;
    right: 180px;
    cursor: pointer;
    transition: color 0.3s;
}

.close-menu:hover {
    color: grey;
}

/* Mobile Styles */
@media (max-width: 768px) {
    .fancy-menu {
        width: 100%;
    }

    .navbar {
        display: flex;
        justify-content: space-between;
    }

    .menu-toggle {
        font-size: 25px;
    }

    .fancy-menu h1 {
        font-size: 1.2em;
    }

    .fancy-menu li {
        padding: 8px 15px;
    }

    .footer-content {
        font-size: 0.8em;
    }
}
   
	</style>
		
</head>
<body>
<header>
        <nav class="navbar">
            <button class="menu-toggle">☰</button>
            <div class="fancy-menu">
                <h1>Dashboard</h1>
                <button class="close-menu">✖</button>
                <ul>
                    <li><a href="../index.php">Home</a></li>
                    <li><a href="../userprofile.php">Profile</a></li>
                    <li><a href="../tasks/tasks_1.php">Tasks</a></li>
                    <li><a href="../journal_final/journal.php">Journal</a></li>
                    <li><a href="../breathe.php">Zen Zone</a></li>
                    <li><a href="../subscriptions/doctor.php">Subscription</a></li>
                    <li><a href="../badges/badges.php">Badges</a></li>
                    
                </ul>
            </div>
        </nav>
    </header>
<div class = "content">
	
    <section class="contacts">
        <h2 style = "text-align: center; color: #0e5066; background-color: white;">Emergency Contact Numbers</h2>
        <div class="contact-card">
            <h3>Police</h3>
            <p><a href="tel:10111">10111</a></p>
        </div>

        <div class="contact-card">
            <h3>Fire Department</h3>
            <p><a href="tel:10177">10177</a></p>
        </div>

        <div class="contact-card">
            <h3>Emergency (Non Crime Related)</h3>
            <p><a href="tel:112">112</a></p>
        </div>

        <div class="contact-card">
            <h3>Mountain Rescue</h3>
            <p>KZN: <a href="tel:0313077744">031 307 7744</a></p>
            <p>Western Cape: <a href="tel:0219489900">021 948 9900</a></p>
            <p>Gauteng: <a href="tel:0741251385">074 125 1385</a> / <a href="tel:0741633952">074 163 3952</a></p>
        </div>

        <div class="contact-card">
            <h3>Poison Emergency Numbers</h3>
            <p>Tygerberg: <a href="tel:0219316129">021 931 6129</a></p>
            <p>Red Cross: <a href="tel:0216895227">021 689 5227</a></p>
            <p>KZN: <a href="tel:0800333444">080 033 3444</a></p>
            <p>Bloemfontein: <a href="tel:0824910160">082 491 0160</a></p>
        </div>

        <div class="contact-card">
            <h3>Mental Health & Child Welfare</h3>
            <p>Lifeline: <a href="tel:0861322322">0861 322 322</a></p>
            <p>Suicide Crisis Line: <a href="tel:0800567567">0800 567 567</a></p>
            <p>SADAG Mental Health Line: <a href="tel:0112344837">011 234 4837</a></p>
        </div>

        <div class="contact-card">
            <h3>Child Abuse</h3>
            <p>Childline: <a href="tel:0800055555">0800 05 55 55</a></p>
        </div>
    </section>

    <section class="custom-contact">
        <h2>Your Own Emergency Contacts</h2>
        <p>If you have personal contacts or a therapist you would like to store for emergencies, you can add them here.</p>
        <form id="contactForm">
            <label for="contactName">Contact Name:</label>
            <input type="text" id="contactName" name="contactName" placeholder = "Enter your Full Name" required>

            <label for="contactNumber">Contact Number:</label>
            <input type="tel" id="contactNumber" name="contactNumber" required pattern="[0-9]{3}[0-9]{3}[0-9]{4}" placeholder="Enter a valid phone number">

			<br><br>
            <button type="submit">Save Contact</button>
        </form>

        <h3>Your Saved Contacts:</h3>
        <div id="savedContactsList"></div> <!-- Placeholder for dynamic contact display -->
    </section>
	</div>

    <footer>
		<div class="footer-content">
        <div class="social-media">
            <p>Follow us on:</p>
            <a href="#" class="social-link">Twitter</a>
            <a href="#" class="social-link">Instagram</a>
        </div>
        <div class="contact-info">
            <p>Email us at: <a href="mailto:moodifysa@gmail.com" style="color: white">moodifysa@gmail.com</a></p>
            <p>Phone: +1-234-567-890</p>
        </div>
    </footer>

    <script>
	// Get the button and the menu
        const menuToggle = document.querySelector('.menu-toggle');
        const fancyMenu = document.querySelector('.fancy-menu');
        const closeMenuButton = document.querySelector('.close-menu');
        const body = document.querySelector('body');

        // Toggle the menu display when the button is clicked
        menuToggle.onclick = function() {
            fancyMenu.classList.toggle('show');
            body.classList.toggle('menu-open');
        };

        // Close the menu when the close button is clicked
        closeMenuButton.onclick = function() {
            fancyMenu.classList.remove('show');
            body.classList.remove('menu-open');
        };


        // Get references to the form and list
        const contactForm = document.getElementById('contactForm');
        const savedContactsList = document.getElementById('savedContactsList');

        // Add event listener for form submission
        contactForm.addEventListener('submit', (event) => {
            event.preventDefault();

            // Get user input
            const contactName = document.getElementById('contactName').value;
            const contactNumber = document.getElementById('contactNumber').value;

            // Create a new contact card
            const contactCard = document.createElement('div');
            contactCard.className = 'contact-card';

            // Add name and clickable phone number
            contactCard.innerHTML = `
                <h4>${contactName}</h4>
                <p><a href="tel:${contactNumber.replace(/\s+/g, '')}">${contactNumber}</a></p>
            `;

            // Append the new contact card to the list
            savedContactsList.appendChild(contactCard);

            // Clear the form inputs
            contactForm.reset();
        });
        contactCard.classList.add('contact-card');
            contactCard.innerHTML = `
                <h3>${contactName}</h3>
                <p><a href="tel:${contactNumber}">${contactNumber}</a></p>
                <button class="delete-contact">Delete</button>
            `;

            // Append the new contact card to the list
            savedContactsList.appendChild(contactCard);

            // Clear the form inputs
            document.getElementById('contactName').value = '';
            document.getElementById('contactNumber').value = '';

            // Add delete functionality
            const deleteButton = contactCard.querySelector('.delete-contact');
            deleteButton.addEventListener('click', () => {
                savedContactsList.removeChild(contactCard);
                
        });
    </script>
</body>
</html>

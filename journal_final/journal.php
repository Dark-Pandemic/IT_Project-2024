<?php
session_start();

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username']; // Use session if available
} elseif (isset($_COOKIE['username'])) {
    $username = $_COOKIE['username']; // Use cookie if session doesn't exist
} else {
    $username = "Guest"; // Fallback for anonymous access
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Journal</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        /* General styles */
        html, body {
            height: 100%; 
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Poppins', sans-serif;
            color: #333;
            background: url('background2.jpg') no-repeat center center/cover;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            min-height: 100vh;
            padding: 20px;
        }

        .welcome {
            text-align: center;
            padding: 50px 20px;
            color: white;
            background-color: rgba(0, 0, 0, 0.5);
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            width: 80%;
            margin-top: 50px;
        }

        h1 {
            color: #a7c7e7;
            font-size: 50px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        p {
            font-size: 20px;
            margin: 20px 0;
            color: #f4e1d2;
        }

        .buttons {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-top: 40px;
        }

        .button {
            background-color: #a7c7e7;
            color: white;
            padding: 15px 30px;
            font-size: 18px;
            text-decoration: none;
            border-radius: 8px;
            transition: transform 0.2s ease, background-color 0.3s;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .button:hover {
            background-color: #85aad6;
            transform: translateY(-4px);
        }
		
        /* Menu styles */
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
                    <li><a href="index.php">Home</a></li>
                    <li><a href="userprofile.php">Profile</a></li>
                    <li><a href="tasks/tasks_1.php">Tasks</a></li>
                    <li><a href="journal_final/journal.php">Journal</a></li>
                    <li><a href="subscriptions/doctor.html">Subscription</a></li>
                    <li><a href="badges/badges.html">Badges</a></li>
                    <li><a href="contacts/contacts_index.php">Emergency Contacts</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <div class="content">
        <center>
            <div class="welcome">
                <h1>Welcome to Your Journal</h1>
                <p>Track your thoughts, emotions, and experiences over time. Journaling helps you reflect and grow. Take a moment to explore.</p>
            </div>
        </center>

        <div class="buttons">
            <a href="customization.php" class="button">Customise your Journal</a>
            <a href="write_entry.php" class="button">Write a New Entry</a>
            <a href="read_entry.php" class="button">Read Previous Entries</a>
        </div>
    </div>

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
    </script>
</body>
</html>

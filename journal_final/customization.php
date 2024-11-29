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
    <title>Customizable Journal</title>
	<link href="https://fonts.googleapis.com/css2?family=Dancing+Script&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Satisfy&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Bokor&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Sour+Gummy&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Sevillana&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: Poppins, sans-serif;
            background-color: #f0f4f8;
            margin: 0;
            padding: 20px;
            overflow-x: hidden;
        }

        table {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            border-spacing: 20px;
        }

        td {
            vertical-align: top;
            padding: 20px;
        }

        .journal-container {
            position: relative;
            display: inline-block;
        }

        .journal {
            position: relative;
            width: 500px;
            height: 700px;
            background-size: cover;
            background-position: center;
            border: 2px solid #ddd;
            border-radius: 0 25px 25px 0;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        .title, .quote {
            position: absolute;
            z-index: 1;
            left: 20px;
            right: 20px;
            text-align: center;
            color: white;
            text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.7);
        }

        .title {
            top: 20px;
            font-size: 20px;
            font-weight: bold;
        }

        .quote {
            bottom: 20px;
            font-size: 16px;
            font-style: italic;
        }

        .journal-pages {
            position: absolute;
            top: 13px;
            right: 0;
            width: 3px;
            height: 680px;
            background: linear-gradient(white, #f7f7f7);
            border: 1px solid #ddd;
            border-radius: 0 28px 28px 0;
            box-shadow: inset 0 0 10px rgba(0, 0, 0, 0.1);
        }

        #controls {
            background: white;
            padding: 20px;
            border: 2px solid #ddd;
            border-radius: 25px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        #controls label {
            font-weight: bold;
        }

        #controls input, #controls select {
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        #controls button {
            padding: 10px;
            border: none;
            background-color: #00aaff;
            color: white;
            font-size: 16px;
            border-radius: 30px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        #controls button:hover {
            background-color: #0088cc;
        }

        .image-selection {
            display: table;
            width: 100%;
            border-spacing: 10px;
        }

        .image-selection img {
            width: 80px;
            height: 120px;
            object-fit: cover;
            border: 2px solid black;
            border-radius: 5px;
            cursor: pointer;
            transition: transform 0.2s, border-color 0.2s;
        }

        .image-selection img:hover {
            transform: scale(1.1);
            border-color: #aaa;
        }

        .light-mode {
            background-color: #f0f4f8;
            color: #333;
        }

        .dark-mode {
            background-color: black;
            color: #ddd;
        }

        .dark-mode .journal {
            background-color: #333;
        }

        body.dark-mode #controls {
            background-color: grey;
        }

        .dark-mode .title, 
        .dark-mode .quote {
            color: #ddd;
        }

        .dark-mode #button {
            background-color: #0e0e55;
        }
		
		.dark-mode .write {
            background-color: #0e0e55;
        }
		
		.dark-mode .previous {
            background-color: white;
        }
		
		.dark-mode .previous:hover {
            background-color: grey;
        }
		

        .dark-mode .journal-pages {
            background: linear-gradient(#333, #444);
            border: 1px solid #555;
        }

        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: 0.4s;
            border-radius: 34px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            border-radius: 50%;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: 0.4s;
        }

        input:checked + .slider {
            background-color: #0e0e55;
        }

        input:checked + .slider:before {
            transform: translateX(26px);
        }
		
		.write{
			width: 35%;
            background-color: #00aaff;
            color: white;
            padding: 15px 20px;
            margin: 30px 0;
            border: none;
            border-radius: 30px;
            cursor: pointer;
			text-decoration: none;
			transition: background-color 0.3s;
		}
		
		.write:hover {
            background-color: #0088cc;
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
<body class="light-mode">
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
<div class = "content">
    <h1 style="text-align: center;">Your Journal</h1>
    <table>
        <tr>
            <td style="width: 60%;">
                <div id="controls">
                    <h2>Customize Your Journal</h2>
                    <label for="title-text">Title:</label>
                    <input type="text" id="title-text" placeholder="Enter journal title" required>
                    
                    <label for="quote-text">Quote:</label>
                    <input type="text" id="quote-text" placeholder="Enter your quote">
                    
                    <label for="font-type">Font Type:</label>
                    <select id="font-type" onchange="changeFont(this)">
                        <option value="Arial">Arial</option>
                        <option value="Courier New">Courier New</option>
                        <option value="Verdana">Verdana</option>
						<option value="Dancing Script" style="font-family: 'Dancing Script', cursive;">Dancing Script</option>
						<option value="Great Vibes" style="font-family: 'Great Vibes', cursive;">Great Vibes</option>
						<option value="Satisfy" style="font-family: 'Satisfy', cursive;">Satisfy</option>
						<option value="Montserrat" style="font-family: 'Montserrat', cursive;">Montserrat</option>
						<option value="Bokor" style="font-family: 'Bokor', cursive;">Bokor</option>
						<option value="Sour Gummy" style="font-family: 'Sour Gummy', cursive;">Sour Gummy</option>
						<option value="Sevillana" style="font-family: 'Sevillana', cursive;">Sevillana</option>
					</select>

                    <label for="font-size">Font Size:</label>
                    <input type="number" id="font-size" value="16" min="8" max="48" step="2">
                    
                    <label for="font-color">Font Color:</label>
                    <input type="color" id="font-color" value="#333333">
                    
                    <h3>Select a Cover Image:</h3>
                    <div class="image-selection" id="image-selection"></div>
                    
                    <center><button onclick="customize()" id="button">Customize</button></center>
                    
                    <center>
					<span>Light/Dark Mode</span><br>
                        <label class="switch">
                            <input type="checkbox" id="theme-toggle" onclick="toggleTheme()">
                            <span class="slider"></span>
                        </label>
                    </center>
                </div>
            </td>

           <td style="width: 30%;">
    <div class="journal-container">
        <div class="journal" id="journal" style="background-image: url('https://via.placeholder.com/300x400.png?text=Default+Cover');">
            <div class="title" id="title">Your Journal Title</div>
            <div class="quote" id="quote"></div>
        </div>
        <div class="journal-pages"></div>
		<br><br><br>
		
        <a href="write_entry.php" class = "write">Write Entry</a>
    </div>
</td>
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
		
    // Define available cover images
    const imageUrls = [
        'beach.jpg',
        'blackandgrey.jpg',
        'brown.jpg',
        'butterfly.jpg',
        'groovy.jpg',
        'plants.jpg',
        'purpleabstract.jpg',
        'strawberry.jpg',
        'space.jpg',
        'shells.jpg',
    ];

    // Selectors for journal and image container
    const journal = document.getElementById('journal');
    const imageSelection = document.getElementById('image-selection');

    // Load saved settings from localStorage on page load
    window.onload = () => {
        const savedData = JSON.parse(localStorage.getItem('journalSettings')) || {};

        document.getElementById('title').innerText = savedData.title || 'Your Journal Title';
        document.getElementById('quote').innerText = savedData.quote || '';
        document.getElementById('font-type').value = savedData.fontType || 'Arial';
        document.getElementById('font-size').value = savedData.fontSize || 16;
        document.getElementById('font-color').value = savedData.fontColor || '#333333';

        // Set journal background if saved
        if (savedData.coverImage) {
            journal.style.backgroundImage = `url('${savedData.coverImage}')`;
        }

        // Apply dark theme if saved
        if (savedData.theme === 'dark') {
            toggleTheme(true);
        }
    };

    // Add images to the selection grid dynamically
    imageUrls.forEach((url) => {
        const img = document.createElement('img');
        img.src = url;
        img.alt = 'Journal Cover';
        img.onclick = () => changeBackground(url);
        imageSelection.appendChild(img);
    });

    // Function to change the journal's cover image
    function changeBackground(imageUrl) {
        journal.style.backgroundImage = `url('${imageUrl}')`;
        saveSettings({ coverImage: imageUrl });
    }

    // Function to save settings in localStorage
    function saveSettings(newSettings) {
        const savedData = JSON.parse(localStorage.getItem('journalSettings')) || {};
        const updatedData = { ...savedData, ...newSettings };
        localStorage.setItem('journalSettings', JSON.stringify(updatedData));
    }

    // Toggle theme between light and dark
    function toggleTheme(forceDark = false) {
        const body = document.body;
        const isDark = body.classList.contains('dark-mode');

        if (forceDark || !isDark) {
            body.classList.add('dark-mode');
            saveSettings({ theme: 'dark' });
        } else {
            body.classList.remove('dark-mode');
            saveSettings({ theme: 'light' });
        }
    }

    // Function to customize font, size, and color
    function customize() {
        const title = document.getElementById('title-text').value;
        const quote = document.getElementById('quote-text').value;
        const fontType = document.getElementById('font-type').value;
        const fontSize = document.getElementById('font-size').value;
        const fontColor = document.getElementById('font-color').value;

        const journalTitle = document.getElementById('title');
        const journalQuote = document.getElementById('quote');

        // Update the journal with customization inputs
        journalTitle.innerText = title;
        journalQuote.innerText = quote;
        journalTitle.style.fontFamily = fontType;
        journalQuote.style.fontFamily = fontType;
        journalTitle.style.fontSize = `${fontSize}px`;
        journalQuote.style.fontSize = `${fontSize - 2}px`;
        journalTitle.style.color = fontColor;
        journalQuote.style.color = fontColor;

        // Save settings to localStorage
        saveSettings({
            title,
            quote,
            fontType,
            fontSize,
            fontColor,
        });
    }

    </script>
</body>
</html>
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
    <title>Customizable Journal</title>
    <style>
        body {
            font-family: Arial, sans-serif;
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
            height: 457px;
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
    </style>
</head>
<body class="light-mode">
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
                    <select id="font-type">
                        <option value="Arial">Arial</option>
                        <option value="Courier New">Courier New</option>
                        <option value="Verdana">Verdana</option>
                    </select>

                    <label for="font-size">Font Size:</label>
                    <input type="number" id="font-size" value="16" min="8" max="36" step="2">
                    
                    <label for="font-color">Font Color:</label>
                    <input type="color" id="font-color" value="#333333">
                    
                    <h3>Select a Cover Image:</h3>
                    <div class="image-selection" id="image-selection"></div>
                    
                    <center><button onclick="customize()" id="button">Customize</button></center>
                    
                    <center>
                        <label class="switch">
                            <input type="checkbox" id="theme-toggle" onclick="toggleTheme()">
                            <span class="slider"></span>
                        </label>
                        <span>Light/Dark Mode</span>
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
                </div>
            </td>
        </tr>
    </table>

    <script>
    const imageUrls = [
        'images/beach.jpg',
        'images/blackandgrey.jpg',
        'images/brown.jpg',
        'images/butterfly.jpg',
        'images/groovy.jpg',
        'images/plants.jpg',
        'images/purpleabtract.jpg',   
        'images/strawberry.jpg',
        'images/space.jpg',
        'images/shells.jpg',
    ];

    const journal = document.getElementById('journal');
    const imageSelection = document.getElementById('image-selection');

    window.onload = () => {
        const savedData = JSON.parse(localStorage.getItem('journalSettings')) || {};
        document.getElementById('title').innerText = savedData.title || 'Your Journal Title';
        document.getElementById('quote').innerText = savedData.quote || '';
        if (savedData.fontType) document.getElementById('font-type').value = savedData.fontType;
        if (savedData.fontSize) document.getElementById('font-size').value = savedData.fontSize;
        if (savedData.fontColor) document.getElementById('font-color').value = savedData.fontColor;
        if (savedData.theme === 'dark') toggleTheme(true);
    };

    imageUrls.forEach(url => {
        const img = document.createElement('img');
        img.src = url;
        img.alt = 'Journal Cover';
        img.onclick = () => changeBackground(url);
        imageSelection.appendChild(img);
    });

    function changeBackground(url) {
        journal.style.backgroundImage = `url(${url})`;
        localStorage.setItem('journalSettings', JSON.stringify({ ...getSettings(), backgroundImage: url }));
    }

    function customize() {
        const titleText = document.getElementById('title-text').value;
        const quoteText = document.getElementById('quote-text').value;
        const fontType = document.getElementById('font-type').value;
        const fontSize = document.getElementById('font-size').value;
        const fontColor = document.getElementById('font-color').value;

        document.getElementById('title').innerText = titleText;
        document.getElementById('quote').innerText = quoteText;
        document.getElementById('title').style.fontFamily = fontType;
        document.getElementById('quote').style.fontFamily = fontType;
        document.getElementById('title').style.fontSize = fontSize + 'px';
        document.getElementById('quote').style.fontSize = fontSize + 'px';
        document.getElementById('title').style.color = fontColor;
        document.getElementById('quote').style.color = fontColor;

        localStorage.setItem('journalSettings', JSON.stringify({
            ...getSettings(),
            title: titleText,
            quote: quoteText,
            fontType,
            fontSize,
            fontColor
        }));
    }

    function getSettings() {
        return JSON.parse(localStorage.getItem('journalSettings')) || {};
    }

    function toggleTheme(isDark = null) {
        const body = document.body;
        if (isDark === null) {
            body.classList.toggle('dark-mode');
        } else {
            body.classList.toggle('dark-mode', isDark);
        }
        localStorage.setItem('journalSettings', JSON.stringify({
            ...getSettings(),
            theme: body.classList.contains('dark-mode') ? 'dark' : 'light'
        }));
    }
    </script>
</body>
</html>

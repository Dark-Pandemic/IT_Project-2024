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
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            gap: 50px;
            flex-wrap: wrap;
        }

        .journal-container {
            position: relative;
            display: inline-block;
        }

        .journal {
            width: 350px;
            height: 480px;
            background-size: cover;
			background-position: center; 
            border: 2px solid #ddd;
            border-radius: 0 25px 25px 0;
            position: relative;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .title {
            position: absolute;
            top: 20px;
            left: 20px;
            right: 20px;
            font-size: 20px;
            font-weight: bold;
            text-align: center;
            color: white;
            text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.7);
        }

        .quote {
            position: absolute;
            bottom: 20px;
            left: 20px;
            right: 20px;
            font-size: 16px;
            color: white;
            font-style: italic;
            text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.7);
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
            width: 300px;
            background: white;
            padding: 20px;
            border: 2px solid #ddd;
            border-radius: 25px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        #controls label {
            font-weight: bold;
            display: block;
            margin: 10px 0 5px;
        }

        #controls input, #controls select {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        #controls button {
            width: 45%;
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
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 20px;
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
		.dark-mode .quote  {
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
    <div class="container">
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
            
            <center><button onclick="customize()" id = "button">Customize</button></center>
			
            <br><br>
			
            <center>
                <label class="switch">
                    <input type="checkbox" id="theme-toggle" onclick="toggleTheme()">
                    <span class="slider"></span>
                </label>
                <span>Light/Dark Mode</span>
            </center>
        </div>

        <div class="journal-container">
            <div class="journal" id="journal" style="background-image: url('https://via.placeholder.com/300x400.png?text=Default+Cover');">
                <div class="title" id="title">Your Journal Title</div>
                <div class="quote" id="quote"></div>
            </div>
            <div class="journal-pages"></div>
        </div>
    </div>

    <div>
        <h3 style="text-align: center;">Select a Cover Image:</h3>
        <div class="image-selection" id="image-selection"></div>
    </div>

    <script>
    const imageUrls = [
        'images/create an image of a calm beachy vibe.jpg',
        'images/31BtUD9DCKL.jpg',
        'images/abstract-art-purple-wallpaper.jpg',
        'images/shells-light-blue-aesthetic-wallpaper-scaled.jpg',
        'images/light-green-aesthetic-cacti-wbm2vhrmcfug7lw4.jpg',
        'images/desktop-wallpaper-84-aesthetic-sun-tablet-aesthetic.jpg',
        'images/5e42d68db4125cebaecd9e67501093a5.jpg',   
        'images/minimalist-brown-aesthetic-shade-of-cloud-eb948m2ol6wqrurz.jpg',
        'images/groovy-wallpaper.jpg',
        'images/HD-wallpaper-butterfly-aes-aesthetic-amarillo-yellow-thumbnail.jpg',
    ];

    const journal = document.getElementById('journal');
    const imageSelection = document.getElementById('image-selection');

    window.onload = () => {
        const savedData = JSON.parse(localStorage.getItem('journalSettings')) || {};
        if (savedData.backgroundImage) changeBackground(savedData.backgroundImage);
        if (savedData.title) document.getElementById('title').innerText = savedData.title;
        if (savedData.quote) document.getElementById('quote').innerText = savedData.quote;
        if (savedData.fontFamily) {
            document.getElementById('title').style.fontFamily = savedData.fontFamily;
            document.getElementById('quote').style.fontFamily = savedData.fontFamily;
        }
        if (savedData.fontSize) {
            document.getElementById('title').style.fontSize = savedData.fontSize + 'px';
            document.getElementById('quote').style.fontSize = savedData.fontSize + 'px';
        }
        if (savedData.fontColor) {
            document.getElementById('title').style.color = savedData.fontColor;
            document.getElementById('quote').style.color = savedData.fontColor;
        }
        if (savedData.theme === 'dark') {
            document.body.classList.add('dark-mode');
            document.getElementById('theme-toggle').checked = true;
        }
    };

    imageUrls.forEach((url, index) => {
        const img = document.createElement('img');
        img.src = url;
        img.alt = `Image ${index + 1}`;
        img.onclick = () => changeBackground(url);
        imageSelection.appendChild(img);
    });
	

    function customize() {
        const titleText = document.getElementById('title-text').value;
        const quoteText = document.getElementById('quote-text').value;
        const fontType = document.getElementById('font-type').value;
        const fontSize = document.getElementById('font-size').value;
        const fontColor = document.getElementById('font-color').value;

        document.getElementById('title').innerText = titleText || 'Your Journal Title';
        document.getElementById('quote').innerText = quoteText;
        document.getElementById('title').style.fontFamily = fontType;
        document.getElementById('quote').style.fontFamily = fontType;
        document.getElementById('title').style.fontSize = fontSize + 'px';
        document.getElementById('quote').style.fontSize = fontSize + 'px';
        document.getElementById('title').style.color = fontColor;
        document.getElementById('quote').style.color = fontColor;

        saveData('title', titleText);
        saveData('quote', quoteText);
        saveData('fontFamily', fontType);
        saveData('fontSize', fontSize);
        saveData('fontColor', fontColor);
    }

    function toggleTheme() {
        const body = document.body;
        const checkbox = document.getElementById('theme-toggle');
        if (checkbox.checked) {
            body.classList.remove('light-mode');
            body.classList.add('dark-mode');
            saveData('theme', 'dark');
        } else {
            body.classList.remove('dark-mode');
            body.classList.add('light-mode');
            saveData('theme', 'light');
        }
    }

    function saveData(key, value) {
        let savedData = JSON.parse(localStorage.getItem('journalSettings')) || {};
        savedData[key] = value;
        localStorage.setItem('journalSettings', JSON.stringify(savedData));
    }
	
</script>

</body>
</html>

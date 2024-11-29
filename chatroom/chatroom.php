<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chatroom - Mental Health Support</title>

    <!-- Inline CSS -->
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        #chatContainer {
            width: 100%;
            max-width: 600px;
            margin: auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }
        #messages {
            height: 400px;
            overflow-y: scroll;
            padding: 10px;
            border-bottom: 1px solid #ccc;
            display: flex;
            flex-direction: column;
            gap: 10px; /* Space between messages */
        }
        .message {
            padding: 10px;
            border-radius: 5px;
            background-color: #e0f7fa; /* Light blue background */
            position: relative;
            max-width: 80%; /* Limit message width */
        }
        .timestamp {
            font-size: 0.8em;
            color: #666;
            position: absolute;
            bottom: 5px;
            right: 10px;
        }
        #messageInput {
            display: flex;
            padding: 10px;
        }
        #messageInput input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        #messageInput button {
            padding: 10px;
            border: none;
            background-color: #00aaff;
            color: white;
            border-radius: 10px;
            margin-left: 6px;
            cursor: pointer;
        }
        #messageInput button:hover {
            background-color: #0088cc; 
        }
		
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
			transform: scale(1.05);
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
			transform: scale(1.05);
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
                <li><a href="../userprofile.php">Profile</a></li>

<li><a href="../tasks/tasks_1.php">Tasks</a></li>

<li><a href="../journal_final/journal.php">Journal</a></li>

<li><a href="../breathe.php">Zen Zone</a></li>

<li><a href="../subscriptions/doctor.php">Subscriptions</a></li>

<li><a href="../badges/badges.php">Badges</a></li>

<li><a href="../contacts/contacts_index.php">Emergency Contact</a></li>


                </ul>
            </div>
        </nav>
    </header>
	<h2 style = "color: #0e5066; text-align: center;">Chat with a Therapist</h2>
<div class = "content">

    <div id="chatContainer">
        <div id="messages"></div>
        <div id="messageInput">
            <input type="text" id="message" placeholder="Type your message here..." />
            <button onclick="sendMessage()">Send</button>
        </div>
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

    <!-- Inline JavaScript -->
    <script>
        const therapistId = 1; // Replace with the actual therapist ID

        // Function to fetch messages
        function fetchMessages() {
            fetch('message-handler.php')
                .then(response => response.json())
                .then(data => {
                    const messagesDiv = document.getElementById('messages');
                    messagesDiv.innerHTML = ''; // Clear existing messages
                    data.forEach(message => {
                        const messageElement = document.createElement('div');
                        messageElement.className = 'message';
                        messageElement.innerHTML = `
                            ${message.message}
                            <span class="timestamp">${new Date(message.timestamp).toLocaleTimeString()}</span>
                        `;
                        messagesDiv.appendChild(messageElement);
                    });
                    messagesDiv.scrollTop = messagesDiv.scrollHeight; // Auto-scroll to the bottom
                });
        }

        // Function to send a message
        function sendMessage() {
            const messageInput = document.getElementById('message');
            const messageText = messageInput.value;

            if (messageText.trim() === '') return; // Prevent sending empty messages

            const formData = new FormData();
            formData.append('therapist_id', therapistId);
            formData.append('message', messageText);

            fetch('message-handler.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    messageInput.value = ''; // Clear input field
                    fetchMessages(); // Refresh messages
                } else {
                    alert(data.message); // Show error message
                }
            });
        }

        // Fetch messages every 5 seconds
        setInterval(fetchMessages, 5000);
        fetchMessages(); // Initial fetch
    </script>

</body>
</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Breathing Exercise</title>
    <style>
        /* General Styling */
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: url('beachwaves.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #ffffff;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        /* Add dimming overlay */
        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* Dimming effect */
            z-index: 1;
            overflow: hidden; /* Ensure particles stay within overlay */
        }

        /* Particle styles */
        .particle {
            position: absolute;
            width: 8px;
            height: 10px;
            background-color: rgba(255, 255, 255, 0.6);
            border-radius: 50%;
            opacity: 1;
            animation: particle-animation 5s infinite ease-in-out;
        }

        @keyframes particle-animation {
            0% {
                transform: translate(0, 0) scale(0.5);
                opacity: 0.7;
            }
            50% {
                transform: translate(200px, 200px) scale(1);
                opacity: 0.9;
            }
            100% {
                transform: translate(0, 0) scale(0.5);
                opacity: 0;
            }
        }

        /* General Body Styles */
        h1 {
            font-size: 3rem;
            margin-top: 0px;
            color: #fff;
            text-shadow: 0px 0px 5px rgba(255, 255, 255, 0.7);
            z-index: 2;
            position: relative;
        }

        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            z-index: 2;
            margin-top: 40px;
        }

        .circle-container {
            width: 290px;
            height: 290px;
            position: relative;
            margin-bottom: 40px;
        }

        .progress-ring {
            position: absolute;
            width: 280px;
            height: 280px;
            border: 10px solid transparent;
            border-top: 10px solid #ffffff;
            border-radius: 50%;
            transform-origin: center;
            transform: rotate(0deg);
            transition: transform 1s linear;
        }

        .circle {
            position: absolute;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.2), rgba(255, 255, 255, 0.1));
            box-shadow: 0 0 15px rgba(255, 255, 255, 0.7);
            animation: pulse 8s infinite ease-in-out;
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.2);
            }
        }

        .instructions {
            position: absolute;
            top: 30%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 1.5rem;
            font-weight: bold;
            color: #ffffff;
        }

        .timer {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 3rem;
            font-weight: bold;
            color: #ffffff;
        }

        /* Buttons */
        .buttons {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }

        button {
            padding: 10px 20px;
            font-size: 1.2rem;
            background-color: rgba(255, 255, 255, 0.8);
            color: #333;
            border: 2px solid #ffffff;
            border-radius: 5px;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        button:hover {
            background-color: rgba(255, 255, 255, 1);
            transform: scale(1.05);
        }

        /* Toggle Menu Button */
        .toggle-btn {
            position: absolute;
            top: 20px;
            left: 20px;
            background-color: rgba(255, 255, 255, 0.7);
            color: #333;
            padding: 10px;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            font-size: 20px;
            z-index: 3;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .toggle-btn:hover {
            background-color: rgba(255, 255, 255, 1);
            transform: scale(1.1);
        }

        /* Side Menu Styles */
        .side-menu {
            position: fixed;
            top: 0;
            left: -300px;
            width: 250px;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            color: #fff;
            padding: 20px;
            transition: left 0.3s ease;
            z-index: 2;
        }

        .side-menu a {
            color: #fff;
            text-decoration: none;
            font-size: 1.5rem;
            display: block;
            margin: 20px 0;
            margin-left: 20px;
        }

        .side-menu a:hover {
            color: #aaa;
        }

        /* Show the side menu when active */
        .side-menu.active {
            left: 0;
        }

    </style>
</head>

<body>
    <!-- Toggle Button -->
    <button class="toggle-btn" id="toggle-btn">☰</button>

    <!-- Side Menu -->
    <div class="side-menu" id="side-menu">
  

    <a href="userprofile.php">Profile</a>

    <a href="tasks/tasks_1.php">Tasks</a>

    <a href="journal_final/journal.php">Journal</a>

    <a href="subscriptions/doctor.html">Subscriptions</a>

    <a href="badges/badges.html">Badges</a>

    <a href="contacts/contacts_index.php">Emergency Contact</a>


    </div>

    <div class="container">
        <h1>Relax and Breathe</h1>

        <!-- Breathing Circle -->
        <div class="circle-container">
            <div class="circle"></div>
            <div class="progress-ring" id="progress-ring"></div>
            <div class="instructions" id="instructions">Press Start</div>
            <div class="timer" id="timer">4</div>
        </div>

        <!-- Buttons -->
        <div class="buttons">
            <button id="start-button">Start</button>
            <button id="stop-button">Stop</button>
        </div>
    </div>

    <script>
        const instructions = document.getElementById("instructions");
        const timerDisplay = document.getElementById("timer");
        const startButton = document.getElementById("start-button");
        const stopButton = document.getElementById("stop-button");
        const progressRing = document.getElementById("progress-ring");
        const toggleBtn = document.getElementById("toggle-btn");
        const sideMenu = document.getElementById("side-menu");

        let timer = 4;
        let isInhaling = true;
        let isHolding = false;
        let isExhaling = false;
        let interval;
        let ringRotation = 0;

        // Toggle the side menu visibility
        toggleBtn.addEventListener("click", () => {
            sideMenu.classList.toggle("active");
        });

        // Function to create a particle effect
        function createParticle() {
            const particle = document.createElement("div");
            particle.classList.add("particle");
            document.body.appendChild(particle);

            // Randomly position particle and animate it
            particle.style.left = `${Math.random() * 100}%`;
            particle.style.top = `${Math.random() * 100}%`;

            // Remove particle after animation ends
            setTimeout(() => {
                particle.remove();
            }, 5000); // Particle duration matches animation duration
        }

        // Create particles continuously
        setInterval(createParticle, 200); // Adjust the interval to control particle density

        // Function to update the breathing instructions and timer
        function updateBreathing() {
            if (timer === 0) {
                if (isInhaling) {
                    isInhaling = false;
                    isHolding = true;
                    timer = 3; // Set timer for hold breath
                    instructions.textContent = "Hold...";
                } else if (isHolding) {
                    isHolding = false;
                    isExhaling = true;
                    instructions.textContent = "Exhale...";
                    timer = 5; // Set timer for exhale
                } else if (isExhaling) {
                    isExhaling = false;
                    setTimeout(() => {
                        isInhaling = true;
                        instructions.textContent = "Inhale...";
                        timer = 4;
                    }, 1000);
                    return;
                }
            }

            timerDisplay.textContent = timer;
            timer--;

            // Update the ring's rotation
            ringRotation += 360 / (isInhaling ? 4 : isHolding ? 4 : 6);
            progressRing.style.transform = `rotate(${ringRotation}deg)`;
        }

        // Start the breathing exercise
        function startExercise() {
            clearInterval(interval);
            timer = 4;
            isInhaling = true;
            isHolding = false;
            isExhaling = false;
            instructions.textContent = "Inhale...";
            timerDisplay.textContent = timer;
            ringRotation = 0;
            progressRing.style.transform = `rotate(0deg)`;

            interval = setInterval(updateBreathing, 1000);
        }

        // Stop the breathing exercise and refresh the page
        function stopExercise() {
            clearInterval(interval);
            instructions.textContent = "Press Start";
            ringRotation = 0;
            progressRing.style.transform = "rotate(0deg)";
            timerDisplay.textContent = "--";

            window.location.reload();
        }

        startButton.addEventListener("click", startExercise);
        stopButton.addEventListener("click", stopExercise);


        document.addEventListener('click', function(event) {
    const menu = document.querySelector('.index-page .fancy-menu');
    const menuButton = document.querySelector('.index-page .menu-toggle');
    
    // Check if the click is outside the menu and the menu button
    if (!menu.contains(event.target) && event.target !== menuButton) {
        menu.classList.remove('show'); // Hide the menu
    }
});

    </script>
</body>

</html>

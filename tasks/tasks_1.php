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
    <title>Task Management</title>
    <style>
        /* General body and background */
        body {
            font-family: 'Verdana', sans-serif;
            background: linear-gradient(to bottom, #fceabb, #f8b195); /* Beachy pastel gradient */
            color: #4d4d4d;
            padding: 20px;
            margin: 0;
            overflow: auto;
        }

        header {
    background: url('header_2.jpg') no-repeat center/cover; 
    width: 100%;
    height: 300px; /* Adjust height to match the chosen aspect ratio */
    border-radius: 15px;
    margin-bottom: 0; /* Reduce margin-bottom */
    position: relative; /* Ensures the header can be overlapped */
        }

    .form-container {
    background: rgba(255, 255, 255, 0.9);
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    max-width: 700px;
    margin: auto;
    margin-top: -50px; /* Adjust this value to control the amount of overlap */
    position: relative;
    z-index: 1; /* Ensures it appears above the header */
    transition: transform 0.3s;
        }

        h2 {
            text-align: center;
            color: #e29578;
            margin-bottom: 20px;
        }

        /* Center the level display */
        .level-display {
            background: #ffddd2;
            color: #4d4d4d;
            padding: 15px;
            border-radius: 10px;
            font-size: 24px;
            margin-bottom: 15px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .xp-progress {
            background: #ffe5d9;
            border-radius: 10px;
            height: 20px;
            width: 100%;
            margin-top: 15px; /* Increased space above the progress bar */
            position: relative;
        }

        .xp-progress-bar {
            background: orangered;
            height: 100%;
            border-radius: 10px;
            transition: width 0.5s;
        }

        /* Tab navigation */
        .tab {
            display: inline-block;
            padding: 10px 20px;
            cursor: pointer;
            background: #faf3dd;
            border-radius: 10px;
            margin-right: 5px;
            color: #4d4d4d;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            transition: background 0.3s, transform 0.2s;
        }

        .tab:hover {
            background: #ffddd2;
            transform: scale(1.05);
        }

        .active-tab {
            background: #ffcab1;
        }

        /* Task item styling with hover effect */
        .task-list {
            margin-top: 20px;
        }

        .task-item {
            background: #fff5e6;
            padding: 15px;
            border-radius: 10px;
            margin: 10px 0;
            position: relative;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            cursor: pointer;
            transition: background 0.3s, transform 0.2s;
        }

        .task-item:hover {
            background: #ffddd2;
            transform: scale(1.02);
        }

        .task-item.completed {
            background: #d4edda;
        }

        .task-description {
            color: #4d4d4d;
            margin-top: 10px;
            font-size: 14px;
            display: none;
        }

        /* Button styling */
        button {
            background-color: #e29578;
            color: #ffffff;
            padding: 10px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            margin-top: 15px; /* Added more space */
            transition: background-color 0.3s, transform 0.2s;
        }

        button:hover {
            background-color: #d47f67;
            transform: scale(1.05);
        }

        button:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }

        /* Toast message */
        .toast {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: #f8b195;
            color: #4d4d4d;
            padding: 20px;
            border-radius: 10px;
            display: none;
            z-index: 1000;
            transition: opacity 0.5s;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            font-size: 18px;
        }

        footer {
            text-align: center;
            margin-top: 30px;
            color: #4d4d4d;
        }
    </style>
</head>
<body>

    <header>
    </header>

    <div class="form-container">
        <h2>Select a Task</h2>
        <div class="level-display">Current Level: <span id="currentLevel">1</span></div>
        <div>XP to Next Level: <strong id="xpToNextLevel">50</strong></div>
        <div class="xp-progress">
            <div class="xp-progress-bar" id="xpProgressBar" style="width: 0%;"></div>
        </div>
        
        <!-- Tab Navigation -->
        <div>
            <div class="tab active-tab" onclick="showTasks('daily')">Daily Tasks</div>
            <div class="tab" onclick="showTasks('weekly')">Weekly Tasks</div>
            <div class="tab" onclick="showTasks('monthly')">Monthly Tasks</div>
        </div>

        <!-- Task List Container -->
        <div class="task-list" id="taskList"></div>
    </div>

    <div class="toast" id="toastMessage"></div>

    <footer>
        <p>&copy; 2024 Moodify</p>
    </footer>

    <audio id="levelUpSound" src="level-up-sound.mp3" preload="auto"></audio>

    <script>
        let currentXP = 0;
        let currentLevel = 1;
        const baseXPToNextLevel = 50;
        const currentLevelElement = document.getElementById('currentLevel');
        const xpToNextLevelElement = document.getElementById('xpToNextLevel');
        const xpProgressBar = document.getElementById('xpProgressBar');
        const toastMessage = document.getElementById('toastMessage');
        const levelUpSound = document.getElementById('levelUpSound');

        function calculateXPToNextLevel(level) {
            return baseXPToNextLevel * Math.pow(level, 2);
        }

        function showTasks(taskType) {
            fetchTasksFromDB(taskType);
        }

        async function fetchTasksFromDB(taskType) {
            try {
                const response = await fetch('fetch_tasks.php?taskType=' + taskType);
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                const tasks = await response.json();

                const taskList = document.getElementById('taskList');
                taskList.innerHTML = ''; // Clear existing tasks

                if (tasks.length === 0) {
                    taskList.innerHTML = `<p>No ${taskType} tasks found.</p>`;
                    return;
                }

                tasks.forEach(task => {
                    const taskItem = document.createElement('div');
                    taskItem.classList.add('task-item');
                    taskItem.setAttribute('data-task-id', task.id);
                    taskItem.setAttribute('data-xp', task.xp);

                    taskItem.innerHTML = `
                        <p>${task.name} (${task.xp} XP)</p>
                        <div class="task-description">${task.description}</div>
                        <div class="progress-bar" style="width: 0%;"></div>
                        <button onclick="completeTask(this, ${task.id})">Complete Task</button>
                    `;

                    taskItem.addEventListener('click', function() {
                        const description = this.querySelector('.task-description');
                        description.style.display = description.style.display === 'none' ? 'block' : 'none';
                    });

                    taskList.appendChild(taskItem);
                });
            } catch (error) {
                console.error('Error fetching tasks:', error);
                document.getElementById('taskList').innerHTML = `<p>Error loading tasks.</p>`;
            }
        }

        function completeTask(button, taskId) {
    const taskItem = button.parentElement;
    const xpEarned = parseInt(taskItem.getAttribute('data-xp'));
    const progressBar = taskItem.querySelector('.progress-bar');
    progressBar.style.width = '100%';
    taskItem.classList.add('completed');
    button.innerText = 'Completed';
    button.disabled = true;

    // Send POST request to update task status in the database
    fetch('update_tasks.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `task_id=${taskId}`
    })
    .then(response => response.text())
    .then(data => {
        if (data !== 'Task updated successfully') {
            console.error('Error updating task:', data);
            showToast('Error updating task. Please try again.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Error updating task. Please try again.');
    });

    currentXP += xpEarned;
    if (currentXP >= calculateXPToNextLevel(currentLevel)) {
        currentLevel++;
        currentXP = 0;
        playLevelUpSound();
        showToast('Level Up! You are now level ' + currentLevel);
    }

    updateLevelDisplay();
}

        function updateLevelDisplay() {
            currentLevelElement.innerText = currentLevel;
            xpToNextLevelElement.innerText = calculateXPToNextLevel(currentLevel) - currentXP;
            xpProgressBar.style.width = (currentXP / calculateXPToNextLevel(currentLevel)) * 100 + '%';
        }

        function showToast(message) {
            toastMessage.innerText = message;
            toastMessage.style.display = 'block';
            setTimeout(() => {
                toastMessage.style.display = 'none';
            }, 3000);
        }

        function playLevelUpSound() {
            levelUpSound.play();
        }

        // Initialize with daily tasks
        showTasks('daily');
    </script>
</body>
</html>

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

<title>Welcome to Moodify</title>

<link rel="stylesheet" href="styles.css">

</head>

<body class="index-page">

<!-- Top Dropdown Menu -->

<header id="header">



<nav class="navbar">

<button class="menu-toggle">☰</button>

<a href="userprofile.php" class="logged-in-user">
    
    <div class="user-info">
        <p><?php echo htmlspecialchars($_SESSION['username']); ?></p> <!-- Username with label -->
    </div>
    &nbsp;
    <img src="" alt="Profile Icon" class="profile-icon"> <!-- Profile Icon -->
    <!--retrive rofile ics from images-->


</a>

<nav class="fancy-menu">

<ul>

<li><a href="userprofile.php">Profile</a></li>

<li><a href="tasks/tasks_1.php">Tasks</a></li>

<li><a href="journal_final/journal.php">Journal</a></li>

<li><a href="subscriptions/doctor.html">Subscriptions</a></li>

<li><a href="badges/badges.html">Badges</a></li>

<li><a href="contacts/contacts_index.php">Emergency Contact</a></li>

</ul>

</nav>

</nav>

</header>

<div class="welcome-section">

<h1>Welcome to Moodify, <?php echo htmlspecialchars($username); ?>!</h1>




<div id="quote" class="quote"></div>

</div>

<!-- User Profile Section -->

<div class="profile-section">

<h2>User Profile</h2>

<div class="profile-info">

<img src="images/profile-placeholder.png" alt="Profile Picture" class="profile-pic">

</div>
<div class="level-up">
  <h3>Level: <span class="user-level">5</span></h3>
  <div class="progress-bar">
    <div class="progress"></div>
  </div>
  <p class="experience-points">Experience: <span class="current-exp">1400</span>/<span class="max-exp">2000</span></p>
</div>

<!-- Daily Tasks Section -->
<div class="daily-tasks">
  <h3>Daily Tasks</h3>
  <ul>
    <li><input type="checkbox" class="task-checkbox"> Meditate for 10 minutes</li>
    <li><input type="checkbox" class="task-checkbox"> Go for a walk</li>
    <li><input type="checkbox" class="task-checkbox"> Read a book</li>
    <li><input type="checkbox" class="task-checkbox"> Write in your journal</li>
    <li><input type="checkbox" class="task-checkbox"> Drink 8 glasses of water</li>
  </ul>
  <button class="view-all-tasks">View All Tasks</button>
</div>
</div>

</div>

<script>

// JavaScript to toggle dropdown menu visibility when the button is clicked
document.querySelector('.menu-toggle').addEventListener('click', function(event) {
    const menu = document.querySelector('.fancy-menu');
    
    // Toggle the 'show' class to display or hide the menu
    menu.classList.toggle('show');
    
    // Prevent click propagation to avoid triggering the document click listener
    event.stopPropagation();
});

// Close the menu if the user clicks outside of it
document.addEventListener('click', function(event) {
    const menu = document.querySelector('.index-page .fancy-menu');
    const menuButton = document.querySelector('.index-page .menu-toggle');
    
    // Check if the click is outside the menu and the menu button
    if (!menu.contains(event.target) && event.target !== menuButton) {
        menu.classList.remove('show'); // Hide the menu
    }
});

// Animate progress bar on page load

window.onload = function() {

const currentExp = 1400; // Current experience

const maxExp = 2000; // Maximum experience

const progressBar = document.querySelector('.progress');

const progressPercentage = (currentExp / maxExp) * 100;

// Set the width of the progress bar with animation

progressBar.style.width = progressPercentage + '%';

};

// Task completion animation

const checkboxes = document.querySelectorAll('.task-checkbox');

checkboxes.forEach(checkbox => {

checkbox.addEventListener('change', function() {

const listItem = this.parentElement;

if (this.checked) {

listItem.classList.add('completed-task');

} else {

listItem.classList.remove('completed-task');

}

});

});

document.addEventListener("DOMContentLoaded", function() {

const quotes = [

"The only way to do great work is to love what you do. – Steve Jobs",

"Believe you can and you're halfway there. – Theodore Roosevelt",

"Your limitation—it's only your imagination.",

"Push yourself, because no one else is going to do it for you.",

"Great things never come from comfort zones.",

"Dream it. Wish it. Do it.",

"Success doesn’t just find you. You have to go out and get it.",

"The harder you work for something, the greater you’ll feel when you achieve it."

];

const quoteElement = document.getElementById("quote");

const randomQuote = quotes[Math.floor(Math.random() * quotes.length)];


quoteElement.textContent = randomQuote; // Display the random quote

});


</script>

<footer class="footer">
  <div class="footer-content">
    <div class="social-media">
      <a href="#" class="social-link">Facebook</a>
      <a href="#" class="social-link">Twitter</a>
      <a href="#" class="social-link">Instagram</a>
    </div>
    <div class="contact-info">
      <p>Contact us: <a href="mailto:info@moodify.com">info@moodify.com</a></p>
      <p>© 2024 Moodify. All rights reserved.</p>
    </div>
  </div>
</footer>

</body>

</html>
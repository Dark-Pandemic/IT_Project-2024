<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Welcome to Moodify</title>

<link rel="stylesheet" href="styles.css">

</head>

<body>

<!-- Top Dropdown Menu -->

<header id="header">

<nav class="navbar">

<button class="menu-toggle">☰</button>

<nav class="fancy-menu">

<ul>

<li><a href="#profile">Profile</a></li>

<li><a href="tasks.html">Tasks</a></li>

<li><a href="journal.php">Journal</a></li>

<li><a href="#avatar">Avatar</a></li>

<li><a href="#badges">Badges</a></li>

<li><a href="#emergency">Emergency Contact</a></li>

</ul>

</nav>

</nav>

</header>

<div class="welcome-section">

<h1>Welcome to Moodify, <span class="username">user</span></h1>

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

// JavaScript to toggle dropdown

document.querySelector('.menu-toggle').addEventListener('click', function() {

const menu = document.querySelector('.fancy-menu');

menu.classList.toggle('show');

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
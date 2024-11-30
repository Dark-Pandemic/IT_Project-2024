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

// Database connection
$host = 'localhost'; // Update with your database host
$user = 'root'; // Update with your database username
$password = ''; // Update with your database password
$dbname = 'mentalhealthapp'; // Update with your database name

$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$profile_pic = 'default_profile.png'; // Default profile picture

if ($username !== "Guest") {
    // Fetch profile picture for logged-in user
    $stmt = $conn->prepare("SELECT profile_pic FROM userloginreg WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $profile_pic = $row['profile_pic'] ?: $profile_pic; // Use default if no profile picture
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Welcome to Moodify</title>

<link rel="stylesheet" href="styles.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

<style>



.logout-popup {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: rgba(0, 0, 0, 0.8);
    color: white;
    padding: 20px;
    border-radius: 10px;
    display: none;  /* Hidden by default */
    text-align: center;
    font-size: 18px;
}

</style>

</head>

<body class="index-page">

<!-- Top Dropdown Menu -->

<header id="header">



<nav class="navbar">

<button class="menu-toggle">☰</button>

<a href="userprofile.php" class="logged-in-user">
    <div class="user-info">
        <p><?php echo htmlspecialchars($username); ?></p> <!-- Display the username -->
    </div>
    &nbsp;
    <img src="<?php echo htmlspecialchars($profile_pic); ?>" alt="Profile Icon" class="profile-icon"> <!-- Display the profile icon -->
</a>

<nav class="fancy-menu">

<ul>

<li><a href="userprofile.php">Profile</a></li>

<li><a href="journal_final/journal.php">Journal</a></li>

<li><a href="breathe.php">Zen Zone</a></li>

<li><a href="subscriptions/doctor.php">Subscriptions</a></li>

<li><a href="contacts/contacts_index.php">Emergency Contact</a></li>

<li><a href="chatroom/chatroom.php">Chat with a Therapist</a></li>

<li><a href="http://localhost:5000/music-recommendation">Tunes for Your Mood</a></li>

<li><a href="reflection/weeklyreflectionform.php">Weekly Reflection</a></li>


<li><a href="javascript:void(0);" onclick="confirmLogout()">Log Out</a></li> <!-- Log Out link -->

</ul>

</nav>

</nav>

</header>

<div class="welcome-section">

<h1>Welcome to Moodify, <?php echo htmlspecialchars($username); ?>!</h1>




<div id="quote" class="quote"></div>

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


// Function to handle logout confirmation and redirection
function confirmLogout() {
    // Add a delay to show the "logging out..." message before redirecting
    setTimeout(function() {
        window.location.href = window.location.href.split('?')[0] + '?logout=true';  // Append '?logout=true' to the URL
    }, 500);  // Small delay to allow the "logging out..." message to be seen
}




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
	<p>Follow us on:</p>
     
    <a href="https://www.instagram.com/moodifysa/profilecard/?igsh=aXp6ejFjcHF2Z2E3" class="social-link">Instagram</a>
        
    </div>
    <div class="contact-info">
	  <p>Email us at: <a href="mailto:moodifysa@gmail.com" style="color: white">moodifysa@gmail.com</a></p>
      <p>© 2024 Moodify. All rights reserved.</p>
    </div>
  </div>
</footer>

<div id="logoutPopup" class="logout-popup" style="display:none;">
    <p>Logging out...</p>
</div>

</body>

</html>
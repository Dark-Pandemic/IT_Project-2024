<?php
session_start();

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username']; // Use session if available
    $user_id = $_SESSION['ID']; // Assuming you store the user ID in the session as well
} elseif (isset($_COOKIE['username'])) {
    $username = $_COOKIE['username']; // Use cookie if session doesn't exist
    $user_id = $_COOKIE['ID']; // Assuming you store the user ID in the cookie as well
} else {
    $username = "Guest"; // Fallback for anonymous access
    $user_id = null; // No user ID for guest
}



// Check if the user is logged out, then destroy session and redirect
if (isset($_GET['logout']) && $_GET['logout'] == 'true') {
  session_unset();
  session_destroy();
  setcookie("username", "", time() - 3600, "/"); // Optional: Delete the cookie
  header("Location: loginform.php"); // Redirect to login page
  exit();
}

include 'db.php';

$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';

switch ($filter) {
    case '7days':
        $sql = "SELECT * FROM journal WHERE ID = ? AND created_at >= NOW() - INTERVAL 7 DAY ORDER BY created_at DESC";
        break;
    case 'month':
        $sql = "SELECT * FROM journal WHERE ID = ? AND created_at >= NOW() - INTERVAL 1 MONTH ORDER BY created_at DESC";
        break;
    default:
        $sql = "SELECT * FROM journal WHERE ID = ? ORDER BY created_at DESC";
        break;
}

$stmt = $conn->prepare($sql);
$stmt->execute();
$entries = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Previous Entries</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f7f7f7;
            background-image: url('background.jpg');
            background-size: cover;
            background-position: center center;
            background-attachment: fixed;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .container {
            width: 80%;
            margin: 50px auto;
            padding: 30px;
            background-color: #ffffff;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            background: linear-gradient(135deg, #f4e1d2, #a7c7e7);
        }
        h1 {
            color: #4e7ea1;
            text-align: center;
            font-size: 36px;
        }
        .entry {
            background-color: #f3f4f6;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }
        .entry h2 {
            color: #4e7ea1;
            font-size: 28px;
        }
        .entry p {
            font-size: 18px;
            color: #333;
            line-height: 1.5;
        }
        .entry small {
            color: #777;
            font-size: 14px;
        }
        .button-container {
            display: flex;
            justify-content: center;
            gap: 10px;
        }
        .filter-button, .back-button, .print-button {
            display: inline-block;
            background-color: #f4e1d2;
            color: white;
            padding: 12px 20px;
            text-align: center;
            border-radius: 8px;
            text-decoration: none;
            margin-top: 20px;
        }
        .filter-button:hover, .back-button:hover, .print-button:hover {
            background-color: #d3b59c;
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
    <script>
		
        function printEntry(entryId) {
            const entry = document.getElementById('entry-' + entryId);
            const printWindow = window.open('', '_blank', 'width=800,height=600');

            // Create a clone of the entry content excluding the button
            const entryContent = entry.cloneNode(true);
            
            // Remove the print button from the cloned content
            const printButton = entryContent.querySelector('.print-button');
            if (printButton) {
                printButton.remove();
            }

            // Open the print window and write the content to it
            printWindow.document.write('<html><head><title>Print Entry</title></head><body>');
            printWindow.document.write(entryContent.innerHTML); // Only content, without the button
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.print();
        }
    </script>
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
	
	<div class = "content">
    <div class="container">
        <h1>Your Journal Entries</h1>
        <div class="button-container">
            <a href="?filter=7days" class="filter-button">Last 7 Days</a>
            <a href="?filter=month" class="filter-button">Last Month</a>
            <a href="?filter=all" class="filter-button">All Entries</a>
        </div>

        <?php if (empty($entries)) { ?>
            <p>No entries found.</p>
        <?php } else { ?>
            <?php foreach ($entries as $entry) { ?>
                <div class="entry" id="entry-<?php echo $entry['userID']; ?>">
                    <h2><?php echo htmlspecialchars($entry['file_name']); ?></h2>
                    <p><?php echo nl2br(htmlspecialchars($entry['file_content'])); ?></p>
                    <p><small>Created on: <?php echo $entry['created_at']; ?></small></p>
                    <!-- Updated print button -->
                    <button class="print-button" onclick="printEntry(<?php echo $entry['userID']; ?>)">Print This Entry</button>
                </div>
            <?php } ?>
        <?php } ?>

        <a href="journal.php" class="back-button">Back to Home</a>
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

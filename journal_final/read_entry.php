<?php
session_start();

// Database Connection
$host = 'localhost'; 
$dbname = 'mentalhealthapp'; 
$user = 'root'; 
$pass = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Check session or cookie for user
if (isset($_SESSION['username']) && isset($_SESSION['ID'])) {
    $username = $_SESSION['username']; // Use session if available
    $user_id = $_SESSION['ID']; // Assuming you store the user ID in the session as well
} elseif (isset($_COOKIE['username']) && isset($_COOKIE['ID'])) {
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
  

$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';

function fetch_entries($filter, $conn, $user_id) {
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

    // Prepare and execute the query in one step
    $stmt = $conn->prepare($sql);
    $stmt->execute([$user_id]); // Directly pass the user ID in an array

    // Fetch all the results and return them
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fetch the entries for the selected filter
$entries = fetch_entries($filter, $conn, $user_id);

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
            color: #0e5066;
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
            background-color: #00aaff;
            color: white;
            padding: 12px 20px;
            text-align: center;
            border-radius: 30px;
            text-decoration: none;
            margin-top: 20px;
        }
        .filter-button:hover, .back-button:hover, .print-button:hover {
            background-color: #0088cc;
			transform: scale(1.05);
        }
		
		/* Side Menu Styles */
        .side-menu {
            position: fixed;
            top: 0;
            left: -300px; /* Start off-screen */
            width: 250px;
            height: 100%;
            background-color: rgba(255, 200, 150, 0.7); /* Peach color with transparency */
            color: #fff;
            padding: 10px;
            transition: left 0.3s ease; /* Smooth transition when opening/closing */
            z-index: 2;
            display: flex;
            flex-direction: column;
            justify-content: center; /* Center the items vertically */
            align-items: center; /* Center the items horizontally */
        }

        /* Menu List Styles */
        .side-menu ul {
            padding: 0;
            margin: 0;
            list-style-type: none; /* Remove bullet points */
            text-align: center; /* Center the list items */
        }

        /* Menu Item Styles */
        .side-menu a {
            color: #fff;
            text-decoration: none;
            font-size: 1.3rem;
            display: block;
            margin: 5px 0; /* Reduced margin to bring items closer */
            padding: 5px 15px; /* Adjusted padding for a more compact appearance */
            border-radius: 20px;
            transition: all 0.3s ease;
        }

        /* Hover Effect for Menu Items */
        .side-menu a:hover {
            background-color: white;
            color: rgba(240, 180, 130, 0.7); /* Slightly darker peach color */


            transform: scale(1.05); /* Make items "pop" on hover */
        }

        /* Show the side menu when active */
        .side-menu.active {
            left: 0; /* Slide in */
        }

        /* Log Out Button Styles */
        .logout-btn {
            background-color: white; /* White background for the button */
            color: rgba(255, 150, 100, 0.8); /* Darker peach color for the text */
            font-size: 1.5rem;
            padding: 12px 20px; /* Adjusted padding for better button size */
            border: none;
            border-radius: 20px;
            cursor: pointer;
            margin-top: 20px; /* Space above the Log Out button */
            margin-bottom: 20px; 
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        /* Hover Effect for Log Out Button */
        .logout-btn:hover {
            background-color: rgba(255, 200, 150, 0.8); /* Darker peach background on hover */
            transform: scale(1.05); /* Button expands slightly on hover */
        }

        /* Toggle Button Styles */
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
    </style>
</head>
<body>
<!-- Menu Toggle Button -->
<button class="toggle-btn">☰</button>

<!-- Side Menu -->
<div class="side-menu">
    <h1 style = "color: white;">Journal</h1>
    <ul class="vertical-menu">
        <a href="index.php">Home</a>
<a href="userprofile.php">Profile</a>
<a href="journal_final/journal.php">Journal</a>
<a href="http://localhost:5000/music-recommendation">Tunes for Your Mood</a>
<a href="chatroom/chatroom.php">Chat with a Therapist</a>
<a href="breath.php">Zen Zone</a>
<a href="subscriptions/doctor.php">Subscriptions</a>
<a href="reflection/weeklyreflectionform.php">Weekly Reflection</a>
<a href="contacts/contacts_index.php">Emergency Contact</a>
        
    </ul>
    <button class="logout-btn">Log Out</button>
</div>
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
            <div class="entry" id="entry-<?php echo $entry['ID']; ?>">
                <h2><?php echo htmlspecialchars($entry['file_name']); ?></h2>
                <p><?php echo nl2br(htmlspecialchars($entry['file_content'])); ?></p>
                <p><small>Created on: <?php echo $entry['created_at']; ?></small></p>
                <button class="print-button" onclick="printEntry(<?php echo $entry['ID']; ?>)">Print This Entry</button>
            </div>
        <?php } ?>
    <?php } ?>

    <a href="journal.php" class="back-button">Back to Home</a>
</div>

<script>
	// Get the button and the side menu
    const menuToggle = document.querySelector('.toggle-btn');
    const sideMenu = document.querySelector('.side-menu');

    // Toggle the side menu display when the button is clicked
    menuToggle.onclick = function() {
        sideMenu.classList.toggle('active'); // Add or remove the 'active' class to slide in/out
    };

    // Optional: Close the menu if the user clicks outside of it
    window.onclick = function(event) {
        if (!event.target.matches('.toggle-btn') && !event.target.closest('.side-menu')) {
            sideMenu.classList.remove('active');
        }
    };


    function printEntry(entryId) {
        const entry = document.getElementById('entry-' + entryId);
        const printWindow = window.open('', '_blank', 'width=800,height=600');
        const entryContent = entry.cloneNode(true);
        const printButton = entryContent.querySelector('.print-button');
        if (printButton) {
            printButton.remove();
        }
        printWindow.document.write('<html><head><title>Print Entry</title></head><body>');
        printWindow.document.write(entryContent.innerHTML);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.print();
    }
</script>

</body>
</html>

<?php
// Close the database connection
$conn = null;
?>

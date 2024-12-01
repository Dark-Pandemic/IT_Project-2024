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

// Get user ID from the database using the username
$userId = null;
if ($username != "Guest") {
    $stmt = $conn->prepare("SELECT ID FROM userloginreg WHERE username = ?");
    $stmt->bind_param("s", $username); // Bind the username parameter
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    if ($user) {
        $userId = $user['ID'];
    }
}

// Fetch journal entries for the user
$journalEntries = [];
if ($userId) {
    $stmt = $conn->prepare("SELECT * FROM journal WHERE ID = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($entry = $result->fetch_assoc()) {
        $journalEntries[] = $entry; // Store journal entries
    }
}

// Predefined image paths
$imagePaths = [
    '../journal_final/beach.jpg',
    '../journal_final/blackandgrey.jpg',
    '../journal_final/brown.jpg',
    '../journal_final/butterfly.jpg',
    '../journal_final/groovy.jpg',
    '../journal_final/plants.jpg',
    '../journal_final/purpleabstract.jpg',
    '../journal_final/strawberry.jpg',
    '../journal_final/space.jpg',
    '../journal_final/shells.jpg'
];

// Handle the image selection and storing the image URL for the user
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['image_url'])) {
    $image_url = $_POST['image_url']; // Image selected by the user

    // Save the selected image URL to the database for the logged-in user
    if ($userId) {
        $stmt = $conn->prepare("UPDATE journal SET image_url = ? WHERE ID = ?");
        $stmt->bind_param("si", $image_url, $userId); // Bind the parameters (image_url and user_id)
        $stmt->execute();

       
       // Display success message
       echo "<div class='success-overlay'>Image updated successfully!</div>";
    } else {
        echo "<div class='error-overlay'>Error: User not found!</div>";
    }
}
?>

<!-- HTML Output: Journal and Customization Containers -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customization & Journal</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your styles (optional) -->

<style>
 body {
            font-family: Poppins, sans-serif;
            background-color: #f0f4f8;
            margin: 0;
            padding: 20px;
            overflow-x: hidden;
        }

        .success-overlay {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%); /* Center the block */
            width: 300px;
            padding: 20px;
            background-color: rgba(0, 128, 0, 0.9); /* Green background with opacity */
            color: white;
            font-size: 18px;
            font-weight: bold;
            text-align: center;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3); /* Subtle shadow */
            z-index: 9999;
            opacity: 0; /* Initially hidden */
            visibility: hidden;
            transition: opacity 2s ease-out, visibility 3s ease-out;
        }

        .success-overlay.show {
            opacity: 1; /* Show overlay */
            visibility: visible; /* Make it visible */
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


        .image-option {
    display: inline-block;
    margin: 10px;
    cursor: pointer;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.image-option img {
    width: 70px;
    height: 100px;
    object-fit: cover;
    border-radius: 5px;
    margin-bottom: 5px;
}

.image-option:hover {
    transform: scale(1.1); /* Slight zoom effect on hover */
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); /* Add a shadow for depth */
}



input[type="radio"] {
    display: block;
    margin: 0 auto;
}

.journal-container {
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

.customization-container{
    background: white;
            padding: 10px;
            border: 2px solid #ddd;
            border-radius: 25px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            gap: 5px;
    
}

/* Apply a nice font globally */
body {
    font-family: 'Arial', sans-serif; /* You can change this to any font you prefer */
}

/* Label Styling */
label {
    font-weight: bold;  /* Make the text bold */
    font-size: 1.1em;    /* Slightly increase font size */
    margin-bottom: 8px;  /* Space below the label */
    display: block;      /* Make the label block level so it takes up full width */
    color: #333;         /* Dark gray color for better readability */
}

/* Input Fields Styling */
input[type="text"],
input[type="file"],
input[type="email"],
input[type="password"] {
    width: 100%;            /* Make the input stretch across the container */
    padding: 12px;          /* Add padding inside the input */
    font-size: 1em;         /* Set font size */
    border: 1px solid #ccc; /* Light border */
    border-radius: 4px;     /* Slightly rounded corners */
    margin-bottom: 12px;    /* Space between inputs */
    box-sizing: border-box; /* Include padding and border in width calculation */
}

/* Button Styling */
button {
            background-color: #FF9966; /* Peach color */
            color: white; /* Text color */
            border: none; /* Remove default border */
            border-radius: 25px; /* Round the corners */
            padding: 12px 24px; /* Add some padding for size */
            font-size: 16px; /* Adjust text size */
            cursor: pointer; /* Change cursor to pointer */
            transition: all 0.3s ease; /* Smooth transition for hover effects */
        }
        
        button:hover {
            background-color: #FF9966; /* Darker peach on hover */
            transform: scale(1.05); /* Slightly enlarge the button */
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1); /* Add subtle shadow */
        }

/* Add some space around the form container */
.customization-container {
    padding: 20px;
    max-width: 500px; /* Limit the width of the form */
    margin: 0 auto;  /* Center the form horizontally */
}



.journal-entry {
    position: relative; /* Make the entry container position relative */
    width: 504px;        /* Ensure it takes up the full width of its parent */
    height: 704px;      /* Define a fixed height for the container */
}

.journal-image {
    position: absolute; /* Position the image absolutely within the container */
    top: 0;             /* Align it to the top */
    left: 0;            /* Align it to the left */
    width: 100%;        /* Make the image stretch across the full width */
    height: 100%;       /* Make the image stretch to fill the container's height */
    object-fit: cover;  /* Ensure the image covers the entire container without distortion */
}


</style>



</head>
<body>

<center><h1> Your Journal</h1></center>


    <!-- Table Layout -->
    <table style="width: 100%; border-spacing: 20px;">
        <tr>
           
            
            <!-- Customization Container Column -->
            <td style="width: 55%; vertical-align: top;">
                <div class="customization-container">
                   
                    <form action="" method="POST">

                    <center><h1>Customize Your Journal</h1></center>

                    <label for="title-text">Title:</label>
                    <input type="text" id="title-text" placeholder="Enter journal title" required>

                    <br><br>

                    <label for="quote-text">Quote:</label>
                    <input type="text" id="quote-text" placeholder="Enter your quote">

                    <br><br>

                    <div>
                    <label for="font-type">Font Type:</label>
                    <select id="font-type" onchange="changeFont(this)">
                        <option value="Arial">Arial</option>
                        <option value="Courier New">Courier New</option>
                        <option value="Verdana">Verdana</option>
						<option value="Dancing Script" style="font-family: 'Dancing Script', cursive;">Dancing Script</option>
						<option value="Great Vibes" style="font-family: 'Great Vibes', cursive;">Great Vibes</option>
						<option value="Satisfy" style="font-family: 'Satisfy', cursive;">Satisfy</option>
						<option value="Montserrat" style="font-family: 'Montserrat', cursive;">Montserrat</option>
						<option value="Bokor" style="font-family: 'Bokor', cursive;">Bokor</option>
						<option value="Sour Gummy" style="font-family: 'Sour Gummy', cursive;">Sour Gummy</option>
						<option value="Sevillana" style="font-family: 'Sevillana', cursive;">Sevillana</option>
					</select>

                    <br><br>

                    <label for="font-size">Font Size:</label>
                    <input type="number" id="font-size" value="16" min="8" max="48" step="2">
                    
                    <br><br>

                    <label for="font-color">Font Color:</label>
                    <input type="color" id="font-color" value="#333333">
                    

                    <br><br>

                        <label for="image_url">Select a Journal Cover:</label><br>
                        
                        <!-- Display predefined images -->
                        <?php foreach ($imagePaths as $image): ?>
                            <div class="image-option">
                                <input type="radio" name="image_url" value="<?php echo $image; ?>" id="image_<?php echo basename($image, '.jpg'); ?>" required>
                                <label for="image_<?php echo basename($image, '.jpg'); ?>">
                                    <img src="<?php echo $image; ?>" alt="Image" class="image-thumbnail">
                                </label>
                            </div>
                        <?php endforeach; ?>
                        <br>
                        <button type="submit">Customize</button>
                    </form>
                </div>
            </td>


             <!-- Journal Container Column -->
             <td style="width: 50%; vertical-align: top;">
                <div class="journal-container" style="background-image: url('https://via.placeholder.com/300x400.png?text=Default+Cover');">
                <div class="title" id="title">Your Journal Title</div>
                <div class="quote" id="quote">Your Quote</div>
                    <?php if (count($journalEntries) > 0): ?>
                        <?php foreach ($journalEntries as $entry): ?>
                            <div class="journal-entry">
                                
                              
                                <?php if (!empty($entry['image_url'])): ?>
                                    <img src="<?php echo htmlspecialchars($entry['image_url']); ?>" alt="Journal Image" class="journal-image">
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No journal entries found.</p>
                    <?php endif; ?>
                </div>
            </td>


        </tr>
    </table>

    <script>
    // Handle real-time image upload
    document.getElementById('file-upload').addEventListener('change', function (event) {
        const fileInput = event.target;
        const journalContainer = document.getElementById('journal-container');

        // Check if a file is selected
        if (fileInput.files && fileInput.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                // Set the background image of the journal container to the uploaded image
                journalContainer.style.backgroundImage = `url(${e.target.result})`;
            };
            reader.readAsDataURL(fileInput.files[0]);
        }
    });

    // Function to handle customization and save settings
    document.getElementById('customize-button').addEventListener('click', function () {
        // Call customize function to apply text and style changes
        customize();
    });

    // Function to handle journal customization
    function customize() {
        // Get values from the form fields
        const title = document.getElementById('title-text').value;
        const quote = document.getElementById('quote-text').value;
        const fontType = document.getElementById('font-type').value;
        const fontSize = document.getElementById('font-size').value;
        const fontColor = document.getElementById('font-color').value;

        // Get the journal elements where the title and quote are displayed
        const journalTitle = document.getElementById('title');
        const journalQuote = document.getElementById('quote');
        const journalContainer = document.getElementById('journal-container');

        // Save values to localStorage before page refresh
        localStorage.setItem('journalSettings', JSON.stringify({
            title,
            quote,
            fontType,
            fontSize,
            fontColor
        }));

        // Save image selection (if applicable)
        const selectedImageRadio = document.querySelector('input[name="image_url"]:checked');
        if (selectedImageRadio) {
            localStorage.setItem('journalImage', selectedImageRadio.value);
        }

        // Trigger a page reload to apply the new settings
        location.reload();
    }

    // Function to apply saved settings from localStorage
    function applySettings() {
    const settings = JSON.parse(localStorage.getItem('journalSettings'));
    const journalTitle = document.getElementById('title');
    const journalQuote = document.getElementById('quote');
    const journalContainer = document.getElementById('journal-container');

    // Check if there are saved settings
    if (settings) {
        // Apply title and quote
        if (settings.title) {
            journalTitle.innerText = settings.title;
        }
        if (settings.quote) {
            journalQuote.innerText = settings.quote;
        }

        // Apply font style
        if (settings.fontType) {
            journalTitle.style.fontFamily = settings.fontType;
            journalQuote.style.fontFamily = settings.fontType;
        }
        if (settings.fontSize) {
            journalTitle.style.fontSize = settings.fontSize + 'px';
            journalQuote.style.fontSize = settings.fontSize + 'px';
        }
        if (settings.fontColor) {
            journalTitle.style.color = settings.fontColor;
            journalQuote.style.color = settings.fontColor;
        }

        // Apply background image if selected
        const selectedImage = localStorage.getItem('journalImage');
        if (selectedImage) {
            journalContainer.style.backgroundImage = `url(${selectedImage})`;
        }
    }
}


</script>



</body>
</html>

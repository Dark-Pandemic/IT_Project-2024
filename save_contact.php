<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $contactName = htmlspecialchars($_POST['contactName']);
    $contactNumber = htmlspecialchars($_POST['contactNumber']);

    // Store the data in a simple file or database. For this example, we'll store it in a text file.
    $file = fopen("emergency_contacts.txt", "a");
    if ($file) {
        $date = date('Y-m-d H:i:s');
        fwrite($file, "[$date] Name: $contactName, Number: $contactNumber\n");
        fclose($file);
        echo "Your contact has been saved successfully!";
    } else {
        echo "There was an error saving your contact. Please try again.";
    }
}
?>

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


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscribe to Doctor Access</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
	<link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: Poppins, sans-serif;
            background: linear-gradient(to bottom, #43a3ec, #60dbe4);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #333;
        }
        .container {
            background-color: #ffffff;
            border-radius: 38px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
            padding: 20px;
            max-width: 400px;
            text-align: center;
        }
        h1 {
            color: #0e5066; 
            margin-bottom: 20px;
        }
        p {
            font-size: 16px;
            margin-bottom: 20px;
            color: #666;
        }
        input[type="hidden"] {
            display: none; /* Hide hidden fields */
        }
        input[type="submit"] {
            background-color: #00aaff; 
            color: white;
            border: none;
            border-radius: 30px;
            padding: 12px 20px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        input[type="submit"]:hover {
            background-color: #0088cc; 
			transform: scale(1.05);
        }
        .footer {
            margin-top: 20px;
            font-size: 14px;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Subscribe for R10/month</h1>
        <p>Gain access to professional mental health support at your convenience.</p>
        <form id="subscriptionForm" action="https://www.payfast.co.za/eng/process" method="POST">
            <input type="hidden" name="merchant_id" value="25979149"> <!-- Your Merchant ID -->
            <input type="hidden" name="merchant_key" value="lnfvdj2rwg575"> <!-- Your Merchant Key -->
            <input type="hidden" name="return_url" value="http://localhost:3000/payment-success"> <!-- Replace with your return URL -->
            <input type="hidden" name="cancel_url" value="http://localhost:3000/payment-cancel"> <!-- Replace with your cancel URL -->
            <input type="hidden" name="notify_url" value="http://localhost:3000/payment-notify"> <!-- Replace with your notify URL -->
            <input type="hidden" name="amount" value="10.00">
            <input type="hidden" name="item_name" value="Doctor Access Subscription">
            <input type="hidden" name="email" value="USER_EMAIL"> <!-- Replace with the user's email -->
            <input type="submit" value="Subscribe Now">
        </form>
        <div id="message" style="margin-top: 20px; color: #4A90E2;"></div>
        <div class="footer">
            <p>Your mental health matters. Take the first step today!</p>
        </div>
    </div>

    <script>
        // Handle return and cancel actions
        function handleReturn() {
            const message = "Thank you for your payment! Your subscription is now active.";
            document.getElementById('message').innerText = message;
            generatePDF(); // Generate PDF proof of payment
        }

        function handleCancel() {
            document.getElementById('message').innerText = "Your payment was canceled. Please try again.";
        }

        // Simulate return and cancel actions for demonstration purposes
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('status')) {
            const status = urlParams.get('status');
            if (status === 'success') {
                handleReturn();
            } else if (status === 'cancel') {
                handleCancel();
            }
        }

        // Function to generate PDF proof of payment
        function generatePDF() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();

            // Add content to the PDF
            doc.setFontSize(20);
            doc.text("Proof of Payment", 20, 20);
            doc.setFontSize(12);
            doc.text("Thank you for your payment!", 20, 40);
            doc.text("Subscription: Doctor Access", 20, 50);
            doc.text("Amount: R10.00", 20, 60);
            doc.text("Date: " + new Date().toLocaleDateString(), 20, 70);
            doc.text("Status: Successful", 20, 80);

            // Save the PDF
            doc.save("proof_of_payment.pdf");
        }
    </script>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Emergency Contacts - Mental Health Awareness</title>
    <link rel="stylesheet" href="stylesContacts.css">
</head>
<body>
    <header>
        <h1>Emergency Contact Numbers</h1>
        <p>Your well-being matters. Here are some emergency numbers you can use in case of any mental health, safety, or medical crisis.</p>
    </header>

    <section class="contacts">
        <h2>Emergency Contact Numbers</h2>
        <div class="contact-card">
            <h3>Police</h3>
            <p>10111</p>
        </div>

        <div class="contact-card">
            <h3>Fire Department</h3>
            <p>10177</p>
        </div>

        <div class="contact-card">
            <h3>Emergency (Non Crime Related)</h3>
            <p>112</p>
        </div>

        <div class="contact-card">
            <h3>Mountain Rescue</h3>
            <p>KZN: 031 307 7744</p>
            <p>Western Cape: 021 948 9900</p>
            <p>Gauteng: 074 125 1385 / 074 163 3952</p>
        </div>

        <div class="contact-card">
            <h3>Poison Emergency Numbers</h3>
            <p>Tygerberg: 021 931 6129</p>
            <p>Red Cross: 021 689 5227</p>
            <p>KZN: 080 033 3444</p>
            <p>Bloemfontein: 082 491 0160</p>
        </div>

        <div class="contact-card">
            <h3>Mental Health & Child Welfare</h3>
            <p>Lifeline: 0861 322 322</p>
            <p>Suicide Crisis Line: 0800 567 567</p>
            <p>SADAG Mental Health Line: 011 234 4837</p>
        </div>

        <div class="contact-card">
            <h3>Child Abuse</h3>
            <p>Childline: 0800 05 55 55</p>
        </div>
    </section>

    <section class="custom-contact">
        <h2>Your Own Emergency Contacts</h2>
        <p>If you have personal contacts or a therapist you would like to store for emergencies, you can add them here.</p>
        <form id="contactForm">
            <label for="contactName">Contact Name:</label>
            <input type="text" id="contactName" name="contactName" required>

            <label for="contactNumber">Contact Number:</label>
            <input type="text" id="contactNumber" name="contactNumber" required>

            <button type="submit">Save Contact</button>
        </form>

        <h3>Your Saved Contacts:</h3>
        <div id="savedContactsList"></div> <!-- Placeholder for dynamic contact display -->

    </section>

    <footer>
        <p>&copy; 2024 Mental Health Awareness Moodify</p>
    </footer>

    <script src="scriptContacts.js"></script>
</body>
</html>
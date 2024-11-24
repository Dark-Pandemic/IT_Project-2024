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
            <p><a href="tel:10111">10111</a></p>
        </div>

        <div class="contact-card">
            <h3>Fire Department</h3>
            <p><a href="tel:10177">10177</a></p>
        </div>

        <div class="contact-card">
            <h3>Emergency (Non Crime Related)</h3>
            <p><a href="tel:112">112</a></p>
        </div>

        <div class="contact-card">
            <h3>Mountain Rescue</h3>
            <p>KZN: <a href="tel:0313077744">031 307 7744</a></p>
            <p>Western Cape: <a href="tel:0219489900">021 948 9900</a></p>
            <p>Gauteng: <a href="tel:0741251385">074 125 1385</a> / <a href="tel:0741633952">074 163 3952</a></p>
        </div>

        <div class="contact-card">
            <h3>Poison Emergency Numbers</h3>
            <p>Tygerberg: <a href="tel:0219316129">021 931 6129</a></p>
            <p>Red Cross: <a href="tel:0216895227">021 689 5227</a></p>
            <p>KZN: <a href="tel:0800333444">080 033 3444</a></p>
            <p>Bloemfontein: <a href="tel:0824910160">082 491 0160</a></p>
        </div>

        <div class="contact-card">
            <h3>Mental Health & Child Welfare</h3>
            <p>Lifeline: <a href="tel:0861322322">0861 322 322</a></p>
            <p>Suicide Crisis Line: <a href="tel:0800567567">0800 567 567</a></p>
            <p>SADAG Mental Health Line: <a href="tel:0112344837">011 234 4837</a></p>
        </div>

        <div class="contact-card">
            <h3>Child Abuse</h3>
            <p>Childline: <a href="tel:0800055555">0800 05 55 55</a></p>
        </div>
    </section>

    <section class="custom-contact">
        <h2>Your Own Emergency Contacts</h2>
        <p>If you have personal contacts or a therapist you would like to store for emergencies, you can add them here.</p>
        <form id="contactForm">
            <label for="contactName">Contact Name:</label>
            <input type="text" id="contactName" name="contactName" required>

            <label for="contactNumber">Contact Number:</label>
            <input type="tel" id="contactNumber" name="contactNumber" required pattern="[0-9+ ]+" placeholder="Enter a valid phone number">

            <button type="submit">Save Contact</button>
        </form>

        <h3>Your Saved Contacts:</h3>
        <div id="savedContactsList"></div> <!-- Placeholder for dynamic contact display -->
    </section>

    <footer>
        <p>&copy; 2024 Mental Health Awareness Moodify</p>
    </footer>

    <script>
        // Get references to the form and list
        const contactForm = document.getElementById('contactForm');
        const savedContactsList = document.getElementById('savedContactsList');

        // Add event listener for form submission
        contactForm.addEventListener('submit', (event) => {
            event.preventDefault();

            // Get user input
            const contactName = document.getElementById('contactName').value;
            const contactNumber = document.getElementById('contactNumber').value;

            // Create a new contact card
            const contactCard = document.createElement('div');
            contactCard.className = 'contact-card';

            // Add name and clickable phone number
            contactCard.innerHTML = `
                <h4>${contactName}</h4>
                <p><a href="tel:${contactNumber.replace(/\s+/g, '')}">${contactNumber}</a></p>
            `;

            // Append the new contact card to the list
            savedContactsList.appendChild(contactCard);

            // Clear the form inputs
            contactForm.reset();
        });
    </script>
</body>
</html>

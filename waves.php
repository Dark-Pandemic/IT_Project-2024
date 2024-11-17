<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ocean Waves at Sunset</title>
    <style>
        /* Body with a sunset gradient */
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            background: linear-gradient(to top, #FFA07A, #FFD1A9, #87CEEB); /* Sunset with pastel orange, peach, and sky blue */
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: flex-end;
        }

        /* Sun in the sky */
        .sun {
            position: absolute;
            top: 40%; /* Adjusted to look natural in the sunset */
            left: 50%;
            transform: translateX(-50%);
            width: 130px;
            height: 130px;
            background: radial-gradient(circle at center, #FFD700, #FFA500); /* Soft yellow to orange */
            border-radius: 50%;
            opacity: 0.85;
            z-index: 0;
            box-shadow: 0 0 60px 30px rgba(255, 165, 0, 0.5); /* Glow for sunset effect */
        }

        /* Wave container */
        .wave-container {
            position: relative;
            width: 100%;
            height: 100%;
            overflow: hidden;
        }

        /* Wave styles (unchanged) */
        .wave {
            position: absolute;
            bottom: -100px;
            width: 250%;
            height: 250px;
            border-radius: 50%;
            animation: wave-animation 8s ease-in-out infinite;
            z-index: 1;
        }

        .wave:nth-child(1) {
            bottom: -100px;
            animation-duration: 10s;
            width: 300%;
            animation-delay: 0s;
            opacity: 0.8;
            background: linear-gradient(to top, rgba(0, 191, 255, 0.9) 0%, rgba(0, 123, 255, 0.9) 100%);
        }

        .wave:nth-child(2) {
            bottom: -60px;
            animation-duration: 15s;
            animation-delay: -3s;
            width: 300%;
            opacity: 0.7;
            background: linear-gradient(to top, rgba(0, 150, 255, 0.8) 0%, rgba(0, 75, 130, 0.8) 100%);
        }

        .wave:nth-child(3) {
            bottom: -40px;
            width: 400%;
            animation-duration: 15s;
            opacity: 0.6;
            background: linear-gradient(to top, rgba(0, 100, 255, 0.7) 0%, rgba(0, 50, 100, 0.7) 100%);
        }

        .wave:nth-child(4) {
            bottom: -30px;
            animation-duration: 12s;
            width: 300%;
            animation-delay: -6s;
            opacity: 0.5;
            background: linear-gradient(to top, rgba(0, 140, 255, 0.7) 0%, rgba(0, 80, 140, 0.7) 100%);
        }

        .wave:nth-child(5) {
            bottom: -20px;
            width: 300%;
            animation-duration: 14s;
            animation-delay: -9s;
            opacity: 0.4;
            background: linear-gradient(to top, rgba(0, 110, 255, 0.6) 0%, rgba(0, 60, 120, 0.6) 100%);
        }

        /* Wave animation */
        @keyframes wave-animation {
            0% { transform: translateX(0); }
            50% { transform: translateX(-50%); }
            100% { transform: translateX(0); }
        }
    </style>
</head>
<body>

    <div class="wave-container">
        <!-- Sun in the sunset sky -->
        <div class="sun"></div>

        <!-- Ocean waves -->
        <div class="wave"></div>
        <div class="wave"></div>
        <div class="wave"></div>
        <div class="wave"></div>
        <div class="wave"></div>
    </div>

</body>
</html>

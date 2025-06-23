<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site Maintenance</title>
    <style>
        :root {
            --primary-color: #4A6CF7;
            --text-color: #2A2A2A;
            --light-gray: #F5F5F5;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
            background-color: var(--light-gray);
            color: var(--text-color);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .maintenance-container {
            text-align: center;
            padding: 2rem;
            max-width: 600px;
        }

        .icon {
            font-size: 64px;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: var(--text-color);
        }

        .message {
            font-size: 1.1rem;
            line-height: 1.6;
            color: #666;
            margin-bottom: 2rem;
        }

        .estimated-time {
            background-color: white;
            padding: 1rem 2rem;
            border-radius: 8px;
            display: inline-block;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .contact {
            margin-top: 2rem;
            font-size: 0.9rem;
            color: #888;
        }

        .contact a {
            color: var(--primary-color);
            text-decoration: none;
        }

        .contact a:hover {
            text-decoration: underline;
        }

        .progress {
            width: 100%;
            height: 4px;
            background-color: #e0e0e0;
            border-radius: 2px;
            margin: 2rem 0;
            overflow: hidden;
        }

        .progress-bar {
            height: 100%;
            background-color: var(--primary-color);
            animation: progress-animation 2s infinite linear;
            width: 30%;
        }

        @keyframes progress-animation {
            0% {
                transform: translateX(-100%);
            }
            100% {
                transform: translateX(400%);
            }
        }

        @media (max-width: 480px) {
            .maintenance-container {
                padding: 1rem;
            }

            h1 {
                font-size: 2rem;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <div class="maintenance-container">
        <div class="icon">
            <i class="fas fa-tools"></i>
        </div>

        <h1>We'll Be Right Back!</h1>

        <div class="message">
            We're currently performing some maintenance on our site to serve you better.
            We apologize for any inconvenience this may cause.
        </div>

        <div class="progress">
            <div class="progress-bar"></div>
        </div>

        <div class="estimated-time">
            <strong>Estimated downtime:</strong> 30-60 Minutes
        </div>

        <div class="contact">
            Need urgent assistance? Contact us at
            <a href="mailto:mhrpciofficial@gmail.com">MHR IT Staff</a>
        </div>
    </div>
</body>
</html>

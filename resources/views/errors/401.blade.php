<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unauthorized</title>
    <style>
        :root {
            --primary-color: #0052cc; /* Blue */
            --secondary-color: #002e5c; /* Navy Blue */
            --text-color: #ffffff;
            --background-color: #f5f7fa;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
            background-color: var(--background-color);
            color: var(--secondary-color);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .error-container {
            text-align: center;
            padding: 2rem;
            max-width: 600px;
        }

        .error-code {
            font-size: 120px;
            font-weight: bold;
            color: var(--primary-color);
            margin-bottom: 0;
            line-height: 1;
        }

        .error-divider {
            width: 100px;
            height: 5px;
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            margin: 20px auto;
            border-radius: 2px;
        }

        h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: var(--secondary-color);
        }

        .message {
            font-size: 1.1rem;
            line-height: 1.6;
            color: #666;
            margin-bottom: 2rem;
        }

        .btn-home {
            display: inline-block;
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 12px 30px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 82, 204, 0.3);
        }

        .btn-home:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0, 82, 204, 0.4);
        }

        .btn-login {
            display: inline-block;
            background: transparent;
            color: var(--primary-color);
            padding: 12px 30px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            border: 2px solid var(--primary-color);
            margin-left: 10px;
        }

        .btn-login:hover {
            background-color: rgba(0, 82, 204, 0.1);
        }

        .icon {
            font-size: 64px;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        @media (max-width: 480px) {
            .error-container {
                padding: 1rem;
            }

            .error-code {
                font-size: 80px;
            }

            h1 {
                font-size: 2rem;
            }
            
            .btn-container {
                display: flex;
                flex-direction: column;
                gap: 10px;
            }
            
            .btn-login {
                margin-left: 0;
            }
        }
        
        .btn-container {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 10px;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <div class="error-container">
        <div class="icon">
            <i class="fas fa-lock"></i>
        </div>
        
        <div class="error-code">401</div>
        <div class="error-divider"></div>
        
        <h1>Unauthorized</h1>

        <div class="message">
            You are not authorized to access this page.
            Please log in or contact the administrator if you believe this is an error.
        </div>

        <div class="btn-container">
            <a href="{{ auth('sanctum')->check() ? url('/api-client') : url('/api-client/login') }}" class="btn-home">
                <i class="fas fa-home"></i> Home
            </a>
            <a href="{{ url('/api-client/login') }}" class="btn-login">
                <i class="fas fa-sign-in-alt"></i> Login
            </a>
        </div>
    </div>
</body>
</html> 
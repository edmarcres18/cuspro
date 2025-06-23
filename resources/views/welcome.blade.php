<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('vendor/adminlte/dist/img/frontmhrhci.jpg');
            background-size: cover;
            background-position: center;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background: linear-gradient(135deg, #fff, #007bff);
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            text-align: center;
            animation: fadeIn 2s;
        }

        h1 {
            color: #000; /* Changed text color to white for better visibility */
            margin-bottom: 20px;
        }

        p {
            color: #000; /* Changed text color to a lighter shade */
            margin-bottom: 30px;
        }

        a {
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
            text-decoration: none;
        }

        button:hover {
            background-color: #0056b3;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        img {
            width: 140px;
            height: 90px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <img src="vendor/adminlte/dist/img/mhrhci.png" alt="MHRPCI-CusPro Logo">
        </div>
        <h1>Welcome to MHRPCI-CusPro!</h1>
        <p>We collecting customers data.</p>
        <a href="/login">Login</a>
    </div>
    <script>
        
    </script>
</body>
</html>

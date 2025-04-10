<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Maintenance - Mirror Your World</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .maintenance-container {
            max-width: 600px;
            width: 90%;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        img.logo {
            width: 80px;
            margin-bottom: 20px;
        }
        h1 {
            color: #333;
        }
        p {
            color: #666;
            line-height: 1.6;
        }
        .retry-btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 20px;
        }
        .retry-btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="maintenance-container">
        <img src="Assets/icon_Logo.png" alt="Logo" class="logo">
        <h1>We'll be back soon!</h1>
        <p>Sorry for the inconvenience. Our system is currently undergoing maintenance or experiencing technical difficulties.</p>
        <p>Please check back later. We appreciate your patience!</p>
        <button class="retry-btn" onclick="window.location.reload()">Try Again</button>
        <!-- <p>If you need immediate assistance, please email us at <a href="mailto:hellodeesy@gmail.com">hellodeesy@gmail.com</a></p> -->
    </div>
</body>
</html>
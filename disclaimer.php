<?php
session_start();

// Function to get client IP address
function getClientIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        return $_SERVER['REMOTE_ADDR'];
    }
}

// If disclaimer is accepted
if (isset($_POST['accept_disclaimer'])) {
    $ip = getClientIP();
    
    // Set session variable
    $_SESSION['disclaimer_accepted'] = true;
    $_SESSION['user_ip'] = $ip;
    
    // Redirect to main page
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Legal Disclaimer - Law Firm</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .disclaimer-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 5px;
        }
        .disclaimer-text {
            max-height: 400px;
            overflow-y: auto;
            padding: 20px;
            border: 1px solid #ddd;
            margin-bottom: 20px;
            background: #f9f9f9;
        }
        .disclaimer-buttons {
            text-align: center;
            margin-top: 20px;
        }
        .btn {
            padding: 10px 20px;
            margin: 0 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .btn-accept {
            background: #2c3e50;
            color: white;
        }
        .btn-decline {
            background: #e74c3c;
            color: white;
        }
    </style>
</head>
<body>
    <div class="disclaimer-container">
        <h1>Legal Disclaimer</h1>
        <div class="disclaimer-text">
            <h2>Important Notice</h2>
            <p>Welcome to our law firm's website. Before proceeding, please read and acknowledge the following disclaimer:</p>
            
            <h3>1. Confidentiality and Privacy</h3>
            <p>This website contains confidential information and case details. By accessing this website, you acknowledge that:</p>
            <ul>
                <li>All information provided is strictly confidential and protected under Indian law</li>
                <li>You will not share, reproduce, or distribute any information without explicit written consent</li>
                <li>Your access to this website is being logged and monitored for security purposes</li>
            </ul>

            <h3>2. Legal Compliance</h3>
            <p>This website operates in compliance with Indian laws and regulations, including:</p>
            <ul>
                <li>Information Technology Act, 2000</li>
                <li>Indian Penal Code</li>
                <li>Bar Council of India Rules</li>
            </ul>

            <h3>3. Data Collection</h3>
            <p>By accessing this website, you consent to the collection of:</p>
            <ul>
                <li>Your IP address</li>
                <li>Access timestamps</li>
            </ul>

            <h3>4. Disclaimer of Liability</h3>
            <p>The information provided on this website is for general informational purposes only and does not constitute legal advice.</p>
        </div>

        <form method="POST" class="disclaimer-buttons">
            <button type="submit" name="accept_disclaimer" class="btn btn-accept">I Accept and Agree</button>
            <button type="button" onclick="window.location.href='https://www.google.com'" class="btn btn-decline">I Decline</button>
        </form>
    </div>
</body>
</html> 
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

// Include database connection
require_once 'config/database.php';

// If disclaimer is accepted
if (isset($_POST['accept_disclaimer'])) {
    $ip = getClientIP();
    $session_id = session_id();
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? null;
    
    // Insert agreement into database
    $stmt = $conn->prepare("INSERT INTO disclaimer_agreements (ip_address, session_id, user_agent) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $ip, $session_id, $user_agent);
    $stmt->execute();
    $stmt->close();

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
            padding: 30px;
            background: #fff;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            border-radius: 8px;
        }
        .disclaimer-text {
            max-height: 500px;
            overflow-y: auto;
            padding: 25px;
            border: 1px solid #e0e0e0;
            margin-bottom: 25px;
            background: #f9f9f9;
            line-height: 1.6;
            font-size: 15px;
        }
        .disclaimer-text h1 {
            color: #2c3e50;
            font-size: 24px;
            margin-bottom: 20px;
            text-align: center;
        }
        .disclaimer-text p {
            margin-bottom: 15px;
        }
        .disclaimer-text ul {
            list-style-type: none;
            padding-left: 20px;
            margin-bottom: 20px;
        }
        .disclaimer-text ul li {
            position: relative;
            padding-left: 20px;
            margin-bottom: 10px;
        }
        .disclaimer-text ul li:before {
            content: "•";
            position: absolute;
            left: 0;
            color: #2c3e50;
        }
        .disclaimer-buttons {
            text-align: center;
            margin-top: 25px;
        }
        .btn {
            padding: 12px 30px;
            margin: 0 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .btn-accept {
            background: #2c3e50;
            color: white;
        }
        .btn-accept:hover:not(:disabled) {
            background: #34495e;
        }
        .btn-decline {
            background: #e74c3c;
            color: white;
        }
        .btn-decline:hover {
            background: #c0392b;
        }
        .checkbox-container {
            margin: 25px 0;
            text-align: left;
            padding: 0 20px;
        }
        .checkbox-container label {
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            font-size: 15px;
            color: #2c3e50;
            line-height: 1.4;
        }
        .checkbox-container input[type="checkbox"] {
            width: 20px;
            height: 20px;
            cursor: pointer;
        }
        .btn-accept:disabled {
            background: #95a5a6;
            cursor: not-allowed;
        }
    </style>
</head>
<body>
    <div class="disclaimer-container">
        <div class="disclaimer-text">
            <h1>Welcome to the website of Lex Juris</h1>
            
            <p>In accordance with the regulations laid down by the Bar Council of India, we are not permitted to solicit work or advertise our services. This website is intended solely for informational purposes and is not an invitation to form a professional relationship.</p>
            
            <p>By clicking "I AGREE", you expressly acknowledge and confirm the following:</p>
            
            <ul>
                <li>You are visiting this site of your own free will to learn more about Lex Juris, its team, and the nature of its professional services.</li>
                <li>The content on this website is meant to provide general information about the firm and does not, in any form, constitute legal advice or a legal opinion.</li>
                <li>Any use of the material on this website is entirely at your own discretion and risk. We make no guarantees or warranties, express or implied, regarding the accuracy or completeness of the content.</li>
                <li>Your viewing or use of this site does not create a client-attorney relationship between you and Lex Juris.</li>
                <li>If you require legal assistance, you should seek it independently from a qualified professional suited to your specific circumstances.</li>
            </ul>

            <p>Lex Juris bears no responsibility for any decision or action taken based on the information provided on this site.</p>
            
            <p>If you understand and accept these terms, please check the box below and click "I AGREE" to continue.</p>
        </div>

        <form method="POST" class="disclaimer-buttons" id="disclaimerForm">
            <div class="checkbox-container">
                <label>
                    <input type="checkbox" id="agreeCheckbox" required>
                    I have read and understood the above disclaimer and agree to all terms and conditions
                </label>
            </div>
            <button type="submit" name="accept_disclaimer" class="btn btn-accept" id="acceptButton" disabled>I AGREE</button>
            <button type="button" onclick="window.location.href='https://www.google.com'" class="btn btn-decline">I DECLINE</button>
        </form>
    </div>

    <script>
        document.getElementById('agreeCheckbox').addEventListener('change', function() {
            document.getElementById('acceptButton').disabled = !this.checked;
        });

        document.getElementById('disclaimerForm').addEventListener('submit', function(e) {
            if (!document.getElementById('agreeCheckbox').checked) {
                e.preventDefault();
                alert('Please check the box to indicate that you have read and agree to the disclaimer.');
            }
        });
    </script>
</body>
</html> 
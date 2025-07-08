<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offline - Lex Juris</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #bc8414;
            --secondary-color: #1a1a2e;
            --text-color: #333;
            --light-bg: #f8f9fa;
            --white: #ffffff;
            --border-color: rgba(188, 132, 20, 0.1);
            --shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: var(--light-bg);
            font-family: 'Poppins', sans-serif;
            color: var(--text-color);
            text-align: center;
            padding: 20px;
            line-height: 1.6;
        }

        .container {
            background: var(--white);
            border-radius: 10px;
            box-shadow: var(--shadow);
            padding: 30px;
            max-width: 600px;
            width: 100%;
            border: 1px solid var(--border-color);
        }

        .logo-placeholder {
            margin-bottom: 20px;
        }

        .logo-placeholder img {
            max-width: 120px;
            height: auto;
        }

        .icon-status-gif {
            width: 100%;
            max-width: 250px;
            height: auto;
            margin-bottom: 20px;
            display: inline-block;
        }

        h1 {
            font-family: 'Playfair Display', serif;
            font-size: 2.2rem;
            color: var(--secondary-color);
            margin-bottom: 15px;
            font-weight: 700;
        }

        p {
            font-size: 1rem;
            color: var(--text-color);
            margin-bottom: 25px;
            max-width: 400px;
            margin-left: auto;
            margin-right: auto;
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: var(--primary-color);
            color: var(--white);
            padding: 10px 25px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 0.95rem;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .btn-action:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
        }

        .btn-action i {
            margin-right: 10px;
        }

        @media (max-width: 768px) {
            .container {
                padding: 25px 20px;
                margin: 15px;
            }
            .icon-status-gif {
                max-width: 200px;
            }
            h1 {
                font-size: 1.8rem;
            }
            p {
                font-size: 0.95rem;
                margin-bottom: 20px;
            }
            .btn-action {
                padding: 8px 20px;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo-placeholder">
            <img src="../assets/images/logo.png" alt="Lex Juris Logo">
        </div>
        <img src="../assets/images/offline.gif" alt="Offline" class="icon-status-gif">
        <h1>Connection Lost</h1>
        <p>We're unable to establish a connection to our servers. Please check your internet connection and try again.</p>
        <a href="../index.php" class="btn-action">
            <i class="fas fa-sync-alt"></i>
            Retry Connection
        </a>
    </div>
</body>
</html> 
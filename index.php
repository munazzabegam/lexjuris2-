<?php
session_start();

// Check if disclaimer has been accepted
if (!isset($_SESSION['disclaimer_accepted']) || $_SESSION['disclaimer_accepted'] !== true) {
    header("Location: disclaimer.php");
    exit();
}

$page_title = "LexJuris - Legal Services";
$current_page = "home";

// Include database connection
require_once 'config/database.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-video-container">
            <div id="heroVideoCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="8000">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <video autoplay muted loop playsinline class="hero-video">
                            <source src="videos/bgvideo3.mp4" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    </div>
                </div>
            </div>
        </div>
        <div class="hero-overlay"></div>
        <div class="container position-relative">
            <div class="row min-vh-100 align-items-center">
                <div class="col-lg-6" data-aos="fade-right" data-aos-delay="200">
                    <div class="logo">
                        <img src="assets/images/logo11.jpg" style="width: 100px; height: 100px;" alt="LexJuris Logo" class="img-fluid rounded-circle">
                    </div>
                    <h1 class="display-4 fw-bold text-white mb-4">Professional Legal Services</h1>
                    <p class="lead text-white mb-4">We provide expert legal solutions for individuals and businesses. Our experienced team is dedicated to protecting your rights and interests.</p>
                    <?php
                    // Fetch active contact numbers from database
                    $query = "SELECT phone FROM contact WHERE is_active = 1 ORDER BY id DESC";
                    $result = $conn->query($query);
                    $phones = [];
                    
                    if ($result && $result->num_rows > 0) {
                        while($contact = $result->fetch_assoc()){
                            if (!empty($contact['phone'])) {
                                $phones[] = $contact['phone'];
                            }
                        }
                    }

                    if (!empty($phones)) {
                        if (count($phones) === 1) {
                            // Only one number, show a simple button
                            echo '<a href="tel:' . htmlspecialchars($phones[0]) . '" class="btn btn-warning btn-lg">Contact Us</a>';
                        } else {
                            // Multiple numbers, show a dropdown
                            echo '<div class="dropdown contact-dropdown">';
                            echo '<button class="btn btn-warning btn-lg dropdown-toggle" type="button" id="contactUsDropdown" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-phone me-2"></i>Contact Us</button>';
                            echo '<ul class="dropdown-menu" aria-labelledby="contactUsDropdown">';
                            foreach ($phones as $phone) {
                                echo '<li><a class="dropdown-item" href="tel:' . htmlspecialchars($phone) . '"><i class="fas fa-mobile-alt me-2"></i>' . htmlspecialchars($phone) . '</a></li>';
                            }
                            echo '</ul>';
                            echo '</div>';
                        }
                    } else {
                        // Optional: Fallback if no numbers are in the DB
                        echo '<a href="#" class="btn btn-warning btn-lg disabled">Contact Information Unavailable</a>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </section>

    <style>
        html, body {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        .hero-section {
            position: relative;
            /* overflow: hidden; */ /* This was hiding the dropdown */
            height: 100vh;
            margin-bottom: 0;
            background: #000;
        }
        .hero-video-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
        }
        .hero-video {
            width: 100%;
            height: 100%;
            object-fit: cover;
            position: absolute;
            top: 0;
            left: 0;
        }
        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.3);
            z-index: 2;
        }
        .carousel-item {
            height: 100vh;
            position: relative;
        }
        .container.position-relative {
            z-index: 3;
        }
        .hero-section + section {
            margin-top: 0 !important;
        }
        
        /* Contact Dropdown Styles */
        .contact-dropdown {
            position: relative;
            z-index: 1060;
        }
        
        .contact-dropdown .dropdown-menu {
            z-index: 1061 !important;
            position: absolute !important;
            top: 100% !important;
            left: 0 !important;
            right: auto !important;
            transform: none !important;
            margin-top: 0.125rem !important;
            background-color: #fff !important;
            border: 1px solid rgba(0, 0, 0, 0.15) !important;
            border-radius: 0.375rem !important;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.175) !important;
            min-width: 10rem !important;
            padding: 0.5rem 0 !important;
        }
        
        .contact-dropdown .dropdown-item {
            display: block !important;
            width: 100% !important;
            padding: 0.25rem 1rem !important;
            clear: both !important;
            font-weight: 400 !important;
            color: #212529 !important;
            text-align: inherit !important;
            text-decoration: none !important;
            white-space: nowrap !important;
            background-color: transparent !important;
            border: 0 !important;
        }
        
        .contact-dropdown .dropdown-item:hover {
            color: #1e2125 !important;
            background-color: #e9ecef !important;
        }
        
        .contact-dropdown .dropdown-item:focus {
            color: #1e2125 !important;
            background-color: #e9ecef !important;
        }
        
        .contact-dropdown .dropdown-toggle::after {
            display: inline-block !important;
            margin-left: 0.255em !important;
            vertical-align: 0.255em !important;
            content: "" !important;
            border-top: 0.3em solid !important;
            border-right: 0.3em solid transparent !important;
            border-bottom: 0 !important;
            border-left: 0.3em solid transparent !important;
        }
        
        /* Ensure dropdown is visible on all devices */
        @media (max-width: 768px) {
            .contact-dropdown .dropdown-menu {
                position: absolute !important;
                transform: none !important;
                top: 100% !important;
                left: 0 !important;
                right: auto !important;
            }
        }

        /* New Contact Us Dropdown Design */
        .contact-dropdown .btn-warning {
            background-color: #ffc107;
            border-color: #ffc107;
            color: #000;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }

        .contact-dropdown .btn-warning:hover,
        .contact-dropdown .btn-warning:focus {
            background-color: #e0a800;
            border-color: #d39e00;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
            transform: translateY(-2px);
        }

        .contact-dropdown .dropdown-menu {
            border-radius: 0.5rem;
            border: none;
            box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.15);
            padding: 0.5rem 0;
            margin-top: 0.5rem;
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .contact-dropdown .dropdown-item {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            color: #343a40;
            transition: all 0.2s ease;
        }

        .contact-dropdown .dropdown-item i {
            color: #ffc107;
            transition: transform 0.2s ease;
        }

        .contact-dropdown .dropdown-item:hover {
            background-color: #f8f9fa;
            color: #000;
            transform: translateX(5px);
        }

        .contact-dropdown .dropdown-item:hover i {
            transform: scale(1.2);
        }
    </style>

    <!-- Services Section -->
    <section class="services-section py-5 pt-5">
        <div class="container" style="margin-top: 5%">
            <div class="row text-center mb-5">
                <div class="col-12" data-aos="fade-up">
                    <h2 class="section-title">Our Services</h2>
                    <p class="section-subtitle">Comprehensive legal solutions for all your needs</p>
                </div>
            </div>
            <div class="row g-4">
                <?php
                $services = [
                    [
                        'icon' => 'fa-shield-alt',
                        'title' => 'Criminal Law',
                        'description' => 'Expert defense and representation in criminal cases and proceedings.'
                    ],
                    [
                        'icon' => 'fa-heart',
                        'title' => 'Family Law',
                        'description' => 'Comprehensive family legal services including divorce, custody, and support.'
                    ],
                    [
                        'icon' => 'fa-money-bill-wave',
                        'title' => 'Cheque Law',
                        'description' => 'Professional handling of cheque-related disputes and legal matters.'
                    ]
                    
                ];

                foreach ($services as $index => $service) {
                    echo '<div class="col-md-4" data-aos="fade-up" data-aos-delay="' . ($index * 200) . '">
                        <div class="service-card">
                            <i class="fas ' . $service['icon'] . ' service-icon"></i>
                            <h3>' . $service['title'] . '</h3>
                            <p>' . $service['description'] . '</p>
                        </div>
                    </div>';
                }
                ?>
            </div>
        </div>
    </section>

    <!-- Why Choose Us Section -->
    <section class="why-choose-us py-5 bg-light">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-12" data-aos="fade-up">
                    <h2 class="section-title">Why Choose Us</h2>
                    <p class="section-subtitle">Experience the difference with our dedicated team</p>
                </div>
            </div>
            <div class="row g-4">
                <?php
                $features = [
                    [
                        'icon' => 'fa-users',
                        'title' => 'Expert Team',
                        'description' => 'Highly qualified and experienced legal professionals.'
                    ],
                    [
                        'icon' => 'fa-clock',
                        'title' => '24/7 Support',
                        'description' => 'Round-the-clock assistance for urgent legal matters.'
                    ],
                    [
                        'icon' => 'fa-handshake',
                        'title' => 'Client Focus',
                        'description' => 'Personalized attention and dedicated service.'
                    ]
                ];

                foreach ($features as $index => $feature) {
                    echo '<div class="col-md-4" data-aos="fade-up" data-aos-delay="' . ($index * 200) . '">
                        <div class="feature-card">
                            <i class="fas ' . $feature['icon'] . ' feature-icon"></i>
                            <h3>' . $feature['title'] . '</h3>
                            <p>' . $feature['description'] . '</p>
                        </div>
                    </div>';
                }
                ?>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section py-5">
        <div class="container">
            <div class="row text-center">
                <?php
                $stats_query = "SELECT number_value, label FROM achievements ORDER BY order_index ASC";
                $stats_result = $conn->query($stats_query);
                
                if ($stats_result && $stats_result->num_rows > 0) {
                    while ($stat = $stats_result->fetch_assoc()) {
                        echo '<div class="col-md-3" data-aos="fade-up" data-aos-delay="' . ($index * 200) . '">
                            <div class="stat-item">
                                <h2 class="counter" data-target="' . $stat['number_value'] . '">0</h2>
                                <p>' . htmlspecialchars($stat['label']) . '</p>
                            </div>
                        </div>';
                        $index++;
                    }
                } else {
                    // Fallback to hardcoded stats if no data in DB
                    $stats = [
                        ['number' => 1500, 'label' => 'Happy Clients'],
                        ['number' => 98, 'label' => 'Success Rate'],
                        ['number' => 25, 'label' => 'Years Experience'],
                        ['number' => 500, 'label' => 'Cases Won']
                    ];

                    foreach ($stats as $index => $stat) {
                        echo '<div class="col-md-3" data-aos="fade-up" data-aos-delay="' . ($index * 200) . '">
                            <div class="stat-item">
                                <h2 class="counter" data-target="' . $stat['number'] . '">0</h2>
                                <p>' . $stat['label'] . '</p>
                            </div>
                        </div>';
                    }
                }
                ?>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class="team-section py-5">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-12" data-aos="fade-up">
                    <h2 class="section-title">Our Team</h2>
                    <p class="section-subtitle">Meet our experienced legal professionals</p>
                </div>
            </div>
            <div class="row g-4 justify-content-center">
                <?php
                // Fetch active team members ordered by order_index
                $team_query = "SELECT * FROM team_members WHERE is_active = 1 ORDER BY order_index ASC LIMIT 6";
                $team_result = $conn->query($team_query);
                
                if ($team_result && $team_result->num_rows > 0) {
                    $index = 0;
                    while ($member = $team_result->fetch_assoc()) {
                        echo '<div class="col-md-4 mb-4" data-aos="zoom-in" data-aos-delay="' . ($index * 100) . '">
                            <div class="main-team-card">
                                <img src="' . (!empty($member['photo']) ? htmlspecialchars($member['photo']) : 'img/team-1.jpg') . '" alt="' . htmlspecialchars($member['full_name']) . '" class="team-img">
                                <div class="team-info-overlay">
                                    <div class="team-header">
                                        <h3>
                                            <a href="' . (!empty($member['portfolio']) ? htmlspecialchars($member['portfolio']) : '#') . '" target="_blank" class="text-white text-decoration-none">
                                                ' . htmlspecialchars($member['full_name']) . '
                                            </a>
                                        </h3>';
                        
                        // Add contact icon if available
                        if (!empty($member['contact'])) {
                            $contact_url = '';
                            $contact_icon = '';
                            
                            // Determine if it's an email or phone number
                            if (filter_var($member['contact'], FILTER_VALIDATE_EMAIL)) {
                                $contact_url = 'mailto:' . $member['contact'];
                                $contact_icon = 'fas fa-envelope';
                            } else {
                                $contact_url = 'tel:' . $member['contact'];
                                $contact_icon = 'fas fa-phone';
                            }
                            
                            echo '<div class="team-contact-icon">
                                <a href="' . htmlspecialchars($contact_url) . '" class="contact-icon-link" title="' . htmlspecialchars($member['contact']) . '">
                                    <i class="' . $contact_icon . '"></i>
                                </a>
                            </div>';
                        }
                        
                        echo '</div>
                                    <p>' . htmlspecialchars($member['education']) . '</p>
                                </div>
                            </div>
                        </div>';
                        $index++;
                    }
                } else {
                    // Fallback if no team members found
                    $team_members = [
                        [
                            'photo' => 'uploads/team_photos/team-1.jpg',
                            'full_name' => 'Omer Farooq Mulki',
                            'education' => 'B.A. (Law), LL.B',
                            'portfolio' => 'portfolio1.html',
                            'contact' => '+1234567890'
                        ],
                        [
                            'photo' => 'uploads/team_photos/team-2.jpg',
                            'full_name' => 'Asif Baikady',
                            'education' => 'B.Com, LL.B',
                            'portfolio' => 'portfolio2.html',
                            'contact' => 'asif@example.com'
                        ],
                        [
                            'photo' => 'uploads/team_photos/team-3.jpg',
                            'full_name' => 'Mahammad Asgar',
                            'education' => 'B.A. (Law), LL.B',
                            'portfolio' => 'portfolio3.html',
                            'contact' => '+9876543210'
                        ]
                    ];

                    foreach ($team_members as $index => $member) {
                        echo '<div class="col-md-4 mb-4" data-aos="zoom-in" data-aos-delay="' . ($index * 100) . '">
                            <div class="main-team-card">
                                <img src="' . htmlspecialchars($member['photo']) . '" alt="' . htmlspecialchars($member['full_name']) . '" class="team-img">
                                <div class="team-info-overlay">
                                    <div class="team-header">
                                        <h3>
                                            <a href="' . (!empty($member['portfolio']) ? htmlspecialchars($member['portfolio']) : '#') . '" target="_blank" class="text-white text-decoration-none">
                                                ' . htmlspecialchars($member['full_name']) . '
                                            </a>
                                        </h3>';
                        
                        // Add contact icon for fallback
                        if (!empty($member['contact'])) {
                            $contact_url = '';
                            $contact_icon = '';
                            
                            if (filter_var($member['contact'], FILTER_VALIDATE_EMAIL)) {
                                $contact_url = 'mailto:' . $member['contact'];
                                $contact_icon = 'fas fa-envelope';
                            } else {
                                $contact_url = 'tel:' . $member['contact'];
                                $contact_icon = 'fas fa-phone';
                            }
                            
                            echo '<div class="team-contact-icon">
                                <a href="' . htmlspecialchars($contact_url) . '" class="contact-icon-link" title="' . htmlspecialchars($member['contact']) . '">
                                    <i class="' . $contact_icon . '"></i>
                                </a>
                            </div>';
                        }
                        
                        echo '</div>
                                    <p>' . htmlspecialchars($member['education']) . '</p>
                                </div>
                            </div>
                        </div>';
                    }
                }
                ?>
            </div>
            <div class="row mt-4">
                <div class="col-12 text-center" data-aos="fade-up" data-aos-delay="200">
                    <a href="our-teams.php" class="btn btn-warning btn-lg">More Teams</a>
                </div>
            </div>
        </div>
    </section>

    <style>
        .main-team-card, .team-card {
            position: relative;
            overflow: hidden;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
            margin-bottom: 20px;
        }
        
        .main-team-card:hover, .team-card:hover {
            transform: translateY(-5px);
        }
        
        .team-img, .team-card img {
            width: 100%;
            height: 350px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        
        .main-team-card:hover .team-img, .team-card:hover img {
            transform: scale(1.05);
        }
        
        .team-info-overlay, .team-info {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.9), rgba(0,0,0,0.4));
            padding: 20px;
            color: white;
            transition: all 0.3s ease;
        }
        
        .main-team-card:hover .team-info-overlay,
        .team-card:hover .team-info {
            background: linear-gradient(to top, rgba(0,0,0,0.95), rgba(0,0,0,0.6));
        }
        
        .team-info-overlay h3, .team-info h3 {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 600;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
        }
        
        .team-info-overlay p, .team-info p {
            margin: 5px 0 0;
            font-size: 0.9rem;
            opacity: 0.9;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
        }
        
        .team-social-links {
            margin-top: 12px;
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            justify-content: center;
        }
        
        .social-link {
            color: white;
            font-size: 1rem;
            transition: all 0.3s ease;
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(5px);
            text-decoration: none;
        }
        
        .social-link:hover {
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        
        /* Platform-specific colors on hover */
        .social-link[title="LinkedIn"]:hover { background: #0077b5; }
        .social-link[title="Twitter"]:hover { background: #1da1f2; }
        .social-link[title="Facebook"]:hover { background: #4267B2; }
        .social-link[title="Instagram"]:hover { background: #E1306C; }
        .social-link[title="GitHub"]:hover { background: #333; }
        .social-link[title="Email"]:hover { background: #EA4335; }
        .social-link[title="Other"]:hover { background: #bc841c; }
        
        .team-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        
        .team-header h3 {
            margin: 0;
            flex: 1;
        }
        
        .team-contact-icon {
            margin-left: 15px;
        }
        
        .contact-icon-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            background: rgba(255, 193, 7, 0.95);
            color: #000;
            border-radius: 50%;
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            border: 2px solid rgba(255, 255, 255, 0.3);
        }
        
        .contact-icon-link:hover {
            background: #fff;
            color: #000;
            transform: scale(1.15) translateY(-2px);
            text-decoration: none;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
            border-color: rgba(255, 193, 7, 0.8);
        }
        
        .contact-icon-link i {
            margin: 0;
        }
    </style>

    <!-- Testimonials Section -->
    <section class="testimonials-section pt-5 bg-light" style="margin-bottom: -20%">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-12">
                    <i class="fas fa-quote-right display-4 text-warning mb-3"></i>
                    <h2 class="section-title">Client Testimonials</h2>
                    <p class="section-subtitle">What our clients say about us</p>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div id="testimonialCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="4000">
                        <div class="carousel-inner">
                            <?php
                            // Fetch active testimonials from database
                            $query = "SELECT * FROM testimonials WHERE is_active = 1 ORDER BY order_index ASC";
                            $result = $conn->query($query);
                            
                            if ($result && $result->num_rows > 0) {
                                $index = 0;
                                while ($testimonial = $result->fetch_assoc()) {
                                    $active_class = ($index === 0) ? 'active' : '';
                                    echo '<div class="carousel-item ' . $active_class . '">';
                                    echo '<div class="testimonial-item text-center">';
                                    if (!empty($testimonial['photo'])) {
                                        echo '<img src="' . htmlspecialchars($testimonial['photo']) . '" alt="' . htmlspecialchars($testimonial['name']) . '" class="testimonial-image rounded-circle mb-3" style="width: 100px; height: 100px; object-fit: cover;">';
                                    }
                                    echo '<p class="testimonial-text">"' . htmlspecialchars($testimonial['testimonial']) . '"</p>';
                                    echo '<h4>' . htmlspecialchars($testimonial['name']) . '</h4>';
                                    if (!empty($testimonial['position'])) {
                                        echo '<p class="testimonial-role">' . htmlspecialchars($testimonial['position']);
                                        if (!empty($testimonial['company'])) {
                                            echo ' at ' . htmlspecialchars($testimonial['company']);
                                        }
                                        echo '</p>';
                                    }
                                    echo '</div>';
                                    echo '</div>';
                                    $index++;
                                }
                            } else {
                                // Fallback if no testimonials found
                                echo '<div class="carousel-item active">';
                                echo '<div class="testimonial-item text-center">';
                                echo '<p class="testimonial-text">"Excellent service and professional team. They helped me through a difficult divorce with compassion and expertise."</p>';
                                echo '<h4>Jane Doe</h4>';
                                echo '<p class="testimonial-role">Family Law Client</p>';
                                echo '</div>';
                                echo '</div>';
                            }
                            ?>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        .testimonial-item {
            padding: 2rem;
            width: 800px;
            margin: 0 auto;
        }
        .testimonial-text {
            font-size: 1.2rem;
            font-style: italic;
            color: #666;
            margin-bottom: 1.5rem;
            line-height: 1.6;
        }
        .testimonial-image {
            border: 3px solid #bc841c;
            padding: 3px;
        }
        .testimonial-item h4 {
            color: #333;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }
        .testimonial-role {
            color: #bc841c;
            font-size: 0.9rem;
            margin-bottom: 0;
        }
        .carousel-control-prev,
        .carousel-control-next {
            width: 5%;
            opacity: 0.8;
        }
        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            background-color: #bc841c;
            border-radius: 50%;
            padding: 1.5rem;
        }

        #testimonialCarousel .carousel-control-prev,
        #testimonialCarousel .carousel-control-next {
            top: 20%; /* Adjusted for better visual alignment */
            transform: translateY(-50%);
            height: 44px; /* Slightly smaller */
            width: 44px;  /* Slightly smaller */
            opacity: 0.7;
            transition: opacity 0.3s ease;
        }

        #testimonialCarousel .carousel-control-prev:hover,
        #testimonialCarousel .carousel-control-next:hover {
            opacity: 1;
        }

        #testimonialCarousel .carousel-control-prev-icon,
        #testimonialCarousel .carousel-control-next-icon {
            padding: 0.9rem; /* Adjusted padding for new size */
            background-size: 50%;
        }
    </style>

    <!-- Team Carousel Gallery -->
    <section class="team-carousel py-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h2 class="section-title text-center mb-5">Our Teams Gallery</h2>
                    <div id="teamCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
                        <div class="carousel-inner">
                            <?php
                            // Fetch active gallery images from database
                            $query = "SELECT * FROM gallery WHERE is_active = 1 ORDER BY order_index ASC";
                            $result = $conn->query($query);
                            
                            if ($result && $result->num_rows > 0) {
                                $index = 0;
                                while ($image = $result->fetch_assoc()) {
                                    $active_class = ($index === 0) ? 'active' : '';
                                    echo '<div class="carousel-item ' . $active_class . '">';
                                    echo '<img src="' . htmlspecialchars($image['image']) . '" class="d-block w-100" alt="Team Image ' . ($index + 1) . '">';
                                    echo '<div class="carousel-caption d-none d-md-block">';
                                    echo '</div>';
                                    echo '</div>';
                                    $index++;
                                }
                            } else {
                                // Fallback if no images found in database
                                echo '<div class="carousel-item active">';
                                echo '<img src="assets/images/teampic4.jpeg" class="d-block w-100" alt="Team Image">';
                                echo '<div class="carousel-caption d-none d-md-block">';
                                echo '</div>';
                                echo '</div>';
                            }
                            ?>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#teamCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#teamCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="assets/js/main.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize the testimonial carousel
            var testimonialCarousel = new bootstrap.Carousel(document.getElementById('testimonialCarousel'), {
                interval: 4000,  // Change slide every 4 seconds
                wrap: true,      // Continuous loop
                keyboard: true,  // Enable keyboard controls
                pause: 'hover'   // Pause on mouse hover
            });
            
            // Ensure contact dropdown works properly
            const contactDropdown = document.getElementById('contactUsDropdown');
            if (contactDropdown) {
                // Initialize Bootstrap dropdown manually if needed
                const dropdown = new bootstrap.Dropdown(contactDropdown, {
                    boundary: 'viewport',
                    display: 'dynamic'
                });
                
                // Add click event listener for better mobile support
                contactDropdown.addEventListener('click', function(e) {
                    e.preventDefault();
                    const dropdownMenu = this.nextElementSibling;
                    if (dropdownMenu && dropdownMenu.classList.contains('dropdown-menu')) {
                        dropdownMenu.classList.toggle('show');
                    }
                });
                
                // Close dropdown when clicking outside
                document.addEventListener('click', function(e) {
                    if (!contactDropdown.contains(e.target)) {
                        const dropdownMenu = contactDropdown.nextElementSibling;
                        if (dropdownMenu && dropdownMenu.classList.contains('dropdown-menu')) {
                            dropdownMenu.classList.remove('show');
                        }
                    }
                });
            }
        });
    </script>
</body>
</html> 

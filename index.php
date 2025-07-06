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
    <meta name="description" content="LexJuris Law Chamber - Best Advocate in Mangalore, Karnataka. Top lawyers providing expert legal services in civil law, criminal law, family law, corporate law, and more. Trusted law chamber with experienced advocates in Mangalore.">
    <meta name="keywords" content="best advocate in mangalore, best lawyer in karnataka, top law chamber mangalore, expert lawyers mangalore, civil law advocate, criminal law lawyer, family law attorney, corporate law firm, legal services karnataka, trusted advocates mangalore">
    <title><?php echo $page_title; ?></title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="assets/images/favicon.png">
    <link rel="apple-touch-icon" href="assets/images/favicon.png">
    <link rel="manifest" href="assets/images/site.webmanifest">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
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
                        <div class="img-bg" style="background-color: #ffffff; backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(50px); display: flex; justify-content: center; align-items: center; position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 2; box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);">
                            <img src="assets/images/logo.png" alt="LexJuris Logo" style="max-width: 80%; height: auto; filter: blur(8px) drop-shadow(0 4px 8px #00000080);">
                        </div>
                        <!-- <video autoplay muted loop playsinline class="hero-video">
                            <source src="videos/bgvideo3.mp4" type="video/mp4">
                            Your browser does not support the video tag.
                        </video> -->
                    </div>
                </div>
            </div>
        </div>
        <div class="hero-overlay"></div>
        <div class="container position-relative">
            <div class="row min-vh-100 align-items-center">
                <div class="col-lg-6" data-aos="fade-right" data-aos-delay="200">
                    <div class="logo">
                        <img src="assets/images/footer_logo.png" style="width: 120px; height: 120px;" alt="LexJuris Logo" class="img-fluid rounded-circle">
                        <!-- <h4 display-4 fw-bold text-black mb-4>Law Chamber</h4> -->
                    </div>
                    <h1 class="display-4 fw-bold text-black mb-4">Professional Legal Services</h1>
                    <p class="lead text-black mb-4">We provide expert legal solutions for individuals and businesses. Our experienced team is dedicated to protecting your rights and interests.</p>
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
        .contact-dropdown .btn-warning {
            background-color: #bc841c;
            border-color: #bc841c;
            color: #000;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }
        .contact-dropdown .btn-warning:hover,
        .contact-dropdown .btn-warning:focus {
            background-color: #bc841c;
            border-color: #bc841c;
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
            color: #bc841c;
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
        /* Services Section Responsive */
        .service-modern-card {
            border: 1px solid #f2f2f2;
            transition: box-shadow 0.2s, transform 0.25s;
            min-width: 0;
            max-width: 340px;
            width: 100%;
        }
        .service-modern-card:hover {
            box-shadow: 0 16px 40px rgba(44,44,44,0.18);
            transform: translateY(-10px) scale(1.04);
        }
        .service-modern-icon {
            margin-bottom: 20px;
        }
        .service-modern-icon svg {
            width: 32px;
            height: 32px;
            display: block;
            transition: color 0.2s, transform 0.2s;
            color: #444;
        }
        .service-modern-card:hover .service-modern-icon {
            background: #bc841c !important;
        }
        .service-modern-card:hover .service-modern-icon svg {
            color:rgb(255, 255, 255) !important;
            transform: scale(1.18);
        }
        .service-modern-num {
            transition: color 0.2s, transform 0.2s;
            color: #ececec;
            z-index: 1;
            top: 0rem;
        }
        .fw-bold {
            margin-top: 20px;
            width: 80% !important;
        }
        .service-modern-card:hover .service-modern-num {
            color: #bc841c !important;
            transform: scale(1.08);
        }
        .btn-modern-gold {
            background: #bc841c;
            color: #fff;
            border-radius: 8px;
            padding: 0.5rem 1.5rem;
            font-weight: 500;
            border: none;
            transition: background 0.2s;
        }
        .btn-modern-gold:hover {
            background: #a97a19;
            color: #fff;
            text-decoration: none;
        }
        .service-modern-card .mb-4.text-muted {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            min-height: 4.5em;
            max-height: 4.5em;
        }
        @media (max-width: 991.98px) {
            .service-modern-card {
                max-width: 100%;
                margin-left: 0 !important;
                margin-right: 0 !important;
            }
        }
        @media (max-width: 767.98px) {
            .service-modern-card {
                max-width: 100%;
                margin: 0 0 1.5rem 0 !important;
                min-height: 0 !important;
            }
            .service-modern-card .mb-4.text-muted {
                min-height: 0;
                max-height: none;
            }
            /* Show only one card per carousel slide on mobile */
            #servicesCarousel .carousel-item .row > .col-md-4 {
                flex: 0 0 100%;
                max-width: 100%;
                margin-bottom: 1.5rem;
                display: flex;
                justify-content: center;
                min-height: 0 !important;
            }
            #servicesCarousel .carousel-item .row {
                flex-wrap: nowrap;
                justify-content: center;
                margin-left: 0;
                margin-right: 0;
                height: auto !important;
                min-height: 0 !important;
            }
            #servicesCarousel .carousel-item {
                height: auto !important;
                min-height: 0 !important;
            }
            #servicesCarousel .carousel-item .row > .col-md-4:not(:first-child) {
                display: none;
            }
            /* Remove extra space under services section */
            .services-section,
            .services-section .container {
                margin-bottom: 0 !important;
                padding-bottom: 0 !important;
            }
            .services-section .row.mb-5,
            .services-section .row.text-center.mb-5 {
                margin-bottom: 0 !important;
            }
            #servicesCarousel .carousel-inner > .carousel-item:last-child {
                margin-bottom: 0 !important;
                padding-bottom: 0 !important;
            }
            /* Remove extra space above Why Choose Us section */
            .why-choose-us {
                margin-top: 0 !important;
            }
            /* Remove any min-height or height on .services-section or .carousel-inner if present */
            .services-section, #servicesCarousel .carousel-inner {
                min-height: 0 !important;
                height: auto !important;
            }
            /* Testimonials responsiveness */
            .testimonials-section .container {
                padding-top: 1.5rem !important;
                margin-bottom: -20% !important;
            }
            .testimonial-item {
                width: 100% !important;
                /* padding: 0.5rem !important; */
            }
            .testimonial-image {
                width: 70px !important;
                height: 70px !important;
            }
            .testimonial-text {
                font-size: 1rem !important;
            }
            /* Team gallery responsiveness */
            .team-carousel {
                padding-top: 1.5rem !important;
                padding-bottom: 1.5rem !important;
                margin-top: 0 !important;
            }
            .team-carousel img {
                width: 100% !important;
                height: auto !important;
                max-height: 220px !important;
                object-fit: cover !important;
            }
            .team-carousel {
                margin-top: 0 !important;
            }
            /* Hide non-active carousel items to remove extra space */
            #testimonialCarousel .carousel-inner > .carousel-item:not(.active),
            #teamCarousel .carousel-inner > .carousel-item:not(.active) {
                display: none !important;
            }
            .team-carousel {
                margin-top: 0 !important;
                padding-top: 0 !important;
            }
        }
        /* Why Choose Us Responsive */
        @media (max-width: 991.98px) {
            .why-choose-us {
                height: auto !important;
                padding-bottom: 2rem;
            }
        }
        @media (max-width: 767.98px) {
            .why-choose-us {
                height: auto !important;
                padding-bottom: 2rem;
            }
            .why-choose-us .row.g-4 > div {
                margin-bottom: 1.5rem;
            }
        }
        /* Stats Section Responsive */
        @media (max-width: 991.98px) {
            .stats-section {
                width: 100% !important;
            }
        }
        @media (max-width: 767.98px) {
            .stats-section {
                width: 100% !important;
                padding: 2rem 0 !important;
            }
            .stats-section .col-md-3 {
                flex: 0 0 100%;
                max-width: 100%;
                margin-bottom: 1.5rem;
            }
        }
        /* Team Section Responsive */
        @media (max-width: 991.98px) {
            .main-team-card, .team-card {
                margin-bottom: 1.5rem;
            }
            .team-img, .team-card img {
                height: 250px;
            }
        }
        @media (max-width: 767.98px) {
            .main-team-card, .team-card {
                margin-bottom: 1.5rem;
            }
            .team-img, .team-card img {
                height: 180px;
            }
        }
        /* Testimonials Responsive */
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
        /* Team Gallery Carousel Responsive */
        @media (max-width: 991.98px) {
            .team-carousel img {
                height: 350px;
                object-fit: cover;
            }
        }
        @media (max-width: 767.98px) {
            .team-carousel img {
                height: 200px;
                object-fit: cover;
            }
        }
        /* Add or update WhatsApp and back-to-top button styles */
        .whatsapp-btn, .whatsapp-fixed-btn {
            width: 100px !important;
            height: 100px !important;
            font-size: 3.2rem !important;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: #25d366;
            color: #fff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
            position: fixed;
            right: 16px;
            bottom: 160px;
            z-index: 9999;
            margin-bottom: 0 !important;
        }

        /* Add space between WhatsApp and back-to-top button */
        .back-to-top-btn, .back-to-top-fixed-btn {
            width: 80px !important;
            height: 80px !important;
            font-size: 2.2rem !important;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: #bc841c;
            color: #fff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
            position: fixed;
            right: 16px;
            bottom: 24px;
            z-index: 9998;
            margin-top: 0 !important;
        }

        @media (max-width: 767.98px) {
            .whatsapp-btn, .whatsapp-fixed-btn {
                width: 100px !important;
                height: 100px !important;
                font-size: 3.2rem !important;
                right: 16px;
                bottom: 160px;
                margin-bottom: 0 !important;
            }
            .back-to-top-btn, .back-to-top-fixed-btn {
                width: 80px !important;
                height: 80px !important;
                font-size: 2.2rem !important;
                right: 16px;
                bottom: 24px;
                margin-top: 0 !important;
            }
        }
        @media (max-width: 768px) {
          section.testimonials-section {
            padding-top: 2.5rem !important;
            padding-bottom: 5rem !important;
            margin-top: 0 !important;
            margin-bottom: 0 !important;
            min-height: 0 !important;
            height: auto !important;
            display: block !important;
          }
          section.testimonials-section > .container {
            padding-top: 0 !important;
            padding-bottom: 0 !important;
            margin-top: 0 !important;
            margin-bottom: 0 !important;
          }
          #testimonialCarousel,
          #testimonialCarousel .carousel-inner,
          #testimonialCarousel .carousel-item {
            min-height: 0 !important;
            height: auto !important;
            padding: 0 !important;
            margin: 0 !important;
          }
          .testimonials-section .row,
          .testimonials-section .mb-5,
          .testimonials-section .mt-5 {
            margin-top: 0 !important;
            margin-bottom: 0 !important;
            padding-top: 0 !important;
            padding-bottom: 0 !important;
          }
          .testimonials-section[style] {
            margin-bottom: 0 !important;
          }
        }
        @media (max-width: 768px) {
          section.team-carousel {
            padding-top: 2.5rem !important;
            padding-bottom: 2.5rem !important;
            margin-top: 0 !important;
            margin-bottom: 0 !important;
            min-height: 0 !important;
            height: auto !important;
            display: block !important;
          }
          section.team-carousel > .container {
            padding-top: 0 !important;
            padding-bottom: 0 !important;
            margin-top: 0 !important;
            margin-bottom: 0 !important;
          }
          #teamCarousel,
          #teamCarousel .carousel-inner,
          #teamCarousel .carousel-item {
            min-height: 0 !important;
            height: auto !important;
            padding: 0 !important;
            margin: 0 !important;
          }
          .team-carousel .row,
          .team-carousel .mb-5,
          .team-carousel .mt-5 {
            margin-top: 0 !important;
            margin-bottom: 0 !important;
            padding-top: 0 !important;
            padding-bottom: 0 !important;
          }
          .team-carousel[style] {
            margin-bottom: 0 !important;
          }
        }
        @media (max-width: 768px) {
          .team-carousel .section-title {
            margin-top: 1.5rem !important;
            margin-bottom: 1.5rem !important;
            padding-top: 0.5rem !important;
            padding-bottom: 0.5rem !important;
          }
        }
        @media (max-width: 768px) {
          .team-section .row.g-4.justify-content-center {
            justify-content: center !important;
            display: flex !important;
            flex-wrap: wrap !important;
          }
          .team-section .col-md-4 {
            margin-left: auto !important;
            margin-right: auto !important;
            float: none !important;
            display: flex !important;
            justify-content: center !important;
          }
          .main-team-card, .team-card {
            margin-left: auto !important;
            margin-right: auto !important;
            float: none !important;
          }
        }
        #servicesCarousel,
        #servicesCarousel .carousel-inner,
        #servicesCarousel .carousel-item {
            height: auto !important;
            min-height: 0 !important;
            margin-bottom: 0 !important;
            padding-bottom: 0 !important;
        }
    </style>

    <!-- Services Section -->
    <section class="services-section" style="padding-top: 4rem; margin-bottom:1rem; padding-bottom:0;">
        <div class="container" style="margin-bottom:0; padding-bottom:0;">
            <div class="row text-center mb-5" style="margin-bottom:0 !important;">
                <div class="col-12" data-aos="fade-up">
                    <h2 class="section-title">Our Services</h2>
                    <p class="section-subtitle">Comprehensive legal solutions for all your needs</p>
                </div>
            </div>
            <?php
            $services = [
                [
                    'img' => 'assets/images/card_images/civil_law_card.jpg',
                    'svg' => 'assets/images/icons/gavel.svg',
                    'title' => 'Civil Law',
                    'desc' => 'Deals with private disputes between individuals or organizations. Includes contract law, tort law (e.g., negligence, defamation), property law, and family law.',
                    'num' => '01',
                    'link' => '#'
                ],
                [
                    'img' => 'assets/images/card_images/criminal_law_card.jpeg',
                    'svg' => 'assets/images/icons/gavel.svg',
                    'title' => 'Criminal Law',
                    'desc' => 'Involves prosecution by the state of wrongful acts (crimes) against society. Includes theft, assault, murder, cybercrime, and white-collar crimes.',
                    'num' => '02',
                    'link' => '#'
                ],
                [
                    'img' => 'assets/images/card_images/family-law.jpg',
                    'svg' => 'assets/images/icons/gavel.svg',
                    'title' => 'Family Law',
                    'desc' => 'Covers matters such as marriage, divorce, child custody, maintenance, adoption, and domestic violence.',
                    'num' => '03',
                    'link' => '#'
                ],
                [
                    'img' => 'assets/images/card_images/Administrative-Law.jpg',
                    'svg' => 'assets/images/icons/gavel.svg',
                    'title' => 'Corporate/Business Law',
                    'desc' => 'Regulates the formation, operation, and dissolution of businesses. Includes mergers & acquisitions, company law, corporate governance, and compliance.',
                    'num' => '04',
                    'link' => '#'
                ],
                [
                    'img' => 'assets/images/card_images/soundblock-Wood-scales-books-stack-background-leather.webp',
                    'svg' => 'assets/images/icons/gavel.svg',
                    'title' => 'Labour & Employment Law',
                    'desc' => 'Governs employer-employee relationships. Covers wages, termination, discrimination, and workplace safety.',
                    'num' => '05',
                    'link' => '#'
                ],
                [
                    'img' => 'assets/images/card_images/real_estate_law.jpeg',
                    'svg' => 'assets/images/icons/gavel.svg',
                    'title' => 'Property / Real Estate Law',
                    'desc' => 'Deals with rights and duties related to real property (land and buildings). Includes transactions, leasing, zoning, and landlord-tenant disputes.',
                    'num' => '06',
                    'link' => '#'
                ],
                [
                    'img' => 'assets/images/card_images/constitutional_law.jpeg',
                    'svg' => 'assets/images/icons/gavel.svg',
                    'title' => 'Constitutional Law',
                    'desc' => 'Involves interpretation and application of the Constitution. Covers fundamental rights, duties, federal structure, and judicial review.',
                    'num' => '07',
                    'link' => '#'
                ],
                [
                    'img' => 'assets/images/card_images/Administrative-Law.jpg',
                    'svg' => 'assets/images/icons/gavel.svg',
                    'title' => 'Administrative Law',
                    'desc' => 'Governs the actions and operations of government agencies. Includes licensing, regulation, and tribunal procedures.',
                    'num' => '08',
                    'link' => '#'
                ],
                [
                    'img' => 'assets/images/card_images/tax_law.jpg',
                    'svg' => 'assets/images/icons/gavel.svg',
                    'title' => 'Tax Law',
                    'desc' => 'Involves the assessment and collection of taxes (income tax, GST, customs, etc.). Includes tax planning, appeals, and litigation.',
                    'num' => '09',
                    'link' => '#'
                ],
                [
                    'img' => 'assets/images/card_images/environmental_law.jpg',
                    'svg' => 'assets/images/icons/gavel.svg',
                    'title' => 'Environmental Law',
                    'desc' => 'Deals with protection of the environment and natural resources. Includes pollution control, forest conservation, and climate change laws.',
                    'num' => '10',
                    'link' => '#'
                ],
                [
                    'img' => 'assets/images/card_images/Intellectual-property.jpg',
                    'svg' => 'assets/images/icons/gavel.svg',
                    'title' => 'Intellectual Property (IP) Law',
                    'desc' => 'Protects creations of the mind such as inventions, trademarks, copyrights, and patents.',
                    'num' => '11',
                    'link' => '#'
                ],
                [
                    'img' => 'assets/images/card_images/cyber_law.jpg',
                    'svg' => 'assets/images/icons/gavel.svg',
                    'title' => 'Cyber Law / Information Technology Law',
                    'desc' => 'Governs digital transactions, data protection, online defamation, cybercrimes, and e-contracts.',
                    'num' => '12',
                    'link' => '#'
                ],
                [
                    'img' => 'assets/images/card_images/consumer_law.jpg',
                    'svg' => 'assets/images/icons/gavel.svg',
                    'title' => 'Consumer Protection Law',
                    'desc' => 'Provides remedies for consumers against unfair trade practices or defective goods/services.',
                    'num' => '13',
                    'link' => '#'
                ],
                [
                    'img' => 'assets/images/card_images/Blog photos_human rights lawyers.jpg',
                    'svg' => 'assets/images/icons/gavel.svg',
                    'title' => 'Human Rights Law',
                    'desc' => "Protects individuals' rights and freedoms, often involving issues like discrimination, unlawful detention, and state abuse.",
                    'num' => '14',
                    'link' => '#'
                ],
                [
                    'img' => 'assets/images/card_images/Finance_law.webp',
                    'svg' => 'assets/images/icons/gavel.svg',
                    'title' => 'Banking and Finance Law',
                    'desc' => 'Involves regulations related to loans, securities, financial institutions, and debt recovery.',
                    'num' => '15',
                    'link' => '#'
                ],
                [
                    'img' => 'assets/images/card_images/ADR.jpg',
                    'svg' => 'assets/images/icons/gavel.svg',
                    'title' => 'Alternative Dispute Resolution (ADR)',
                    'desc' => 'Methods like arbitration, mediation, and conciliation to resolve disputes outside court.',
                    'num' => '16',
                    'link' => '#'
                ],
                [
                    'img' => 'assets/images/card_images/international-law-.jpg',
                    'svg' => 'assets/images/icons/gavel.svg',
                    'title' => 'International Law',
                    'desc' => 'Governs relations between nations; includes treaties, international trade, human rights, and conflict resolution.',
                    'num' => '17',
                    'link' => '#'
                ]
            ];
            ?>
            <?php
            $cardsPerSlide = 3;
            $total = count($services);
            $slides = [];
            for ($i = 0; $i < $total; $i++) {
                $slide = [];
                for ($j = 0; $j < $cardsPerSlide; $j++) {
                    $slide[] = $services[($i + $j) % $total];
                }
                $slides[] = $slide;
                if (count($slides) >= $total) break;
            }
            ?>
            <div id="servicesCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="2000">
                <div class="carousel-inner">
                    <?php foreach ($slides as $slideIndex => $serviceChunk): ?>
                        <div class="carousel-item<?= $slideIndex === 0 ? ' active' : '' ?>">
                            <div class="row justify-content-center">
                                <?php foreach ($serviceChunk as $s): ?>
                                    <div class="col-md-4 d-flex align-items-stretch">
                                        <div class="service-modern-card position-relative bg-white rounded-4 shadow-sm p-4 mx-3 w-100" style="min-width:340px;max-width:340px;">
                                            <div class="service-img-wrap position-relative">
                                                <img src="<?= $s['img'] ?>" class="img-fluid rounded-3 w-100" style="height:220px;object-fit:cover;">
                                                <span class="service-modern-icon position-absolute top-100 start-0 translate-middle-y bg-white rounded-3 shadow d-flex align-items-center justify-content-center" style="width:56px;height:56px;left:24px;top:180px;">
                                                    <?= preg_replace('/fill=["\'].*?["\']/', 'fill="currentColor"', file_get_contents($s['svg'])) ?>
                                                </span>
                                            </div>
                                            <div class="pt-4 position-relative" style="margin-top:5%;">
                                                <h5 class="fw-bold mb-2" style="font-size:1.5rem;"><?= $s['title'] ?></h5>
                                                <p class="mb-4 text-muted"><?= $s['desc'] ?></p>
                                                <span class="service-modern-num position-absolute" style="right:0;margin-top:-10%;font-size:4.5rem;font-weight:700;z-index:0;"><?= $s['num'] ?></span>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Choose Us Section -->
    <section class="why-choose-us py-2 position-relative" style="background: url('assets/images/image2.jpg') center center/cover no-repeat;height: 80vh;">
        <div class="why-choose-overlay position-absolute w-100 h-100" style="top:0;left:0;background:rgba(0,0,0,0.7);z-index:1;"></div>
        <div class="container position-relative" style="z-index:2;">
            <div class="row text-center mb-5">
                <div class="col-12" data-aos="fade-up">
                    <h2 class="section-title text-white"style="padding-top: 2%;">Why Choose Us</h2>
                    <p class="section-subtitle text-white-50">Experience the difference with our dedicated team</p>
                </div>
            </div>
            <div class="row g-4">
                <?php
                $features = [
                    [
                        'svg' => 'assets/images/icons/team.svg',
                        'title' => 'Expert Team',
                        'description' => 'Highly qualified and experienced legal professionals.'
                    ],
                    [
                        'svg' => 'assets/images/icons/support.svg',
                        'title' => '24/7 Support',
                        'description' => 'Round-the-clock assistance for urgent legal matters.'
                    ],
                    [
                        'svg' => 'assets/images/icons/focus.svg',
                        'title' => 'Client Focus',
                        'description' => 'Personalized attention and dedicated service.'
                    ]
                ];

                foreach ($features as $index => $feature) {
                    echo '<div class="col-md-4" data-aos="fade-up" data-aos-delay="' . ($index * 200) . '">'
                        . '<div class="feature-card text-white bg-transparent">'
                        . '<span class="feature-svg-icon" style="display:inline-block;width:5rem;height:5rem;vertical-align:middle;margin-bottom:1rem;">'
                        . file_get_contents($feature['svg'])
                        . '</span>'
                        . '<h3>' . $feature['title'] . '</h3>'
                        . '<p>' . $feature['description'] . '</p>'
                        . '</div>'
                        . '</div>';
                }
                ?>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section py-5" style="background: #000;width: 80%; margin: 0 auto;margin-top: -5%;">
        <div class="container">
            <div class="row text-center">
                <?php
                $stats_query = "SELECT number_value, label FROM achievements ORDER BY order_index ASC";
                $stats_result = $conn->query($stats_query);
                
                if ($stats_result && $stats_result->num_rows > 0) {
                    while ($stat = $stats_result->fetch_assoc()) {
                        echo '<div class="col-md-3" data-aos="fade-up" data-aos-delay="' . ($index * 200) . '" style="color: #fff;">'
                            . '<div class="stat-item">'
                            . '<h2 class="counter" data-target="' . $stat['number_value'] . '" style="color: #bc841c;">0</h2>'
                            . '<p>' . htmlspecialchars($stat['label']) . '</p>'
                            . '</div>'
                            . '</div>';
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
                        echo '<div class="col-md-3" data-aos="fade-up" data-aos-delay="' . ($index * 200) . '" style="color: #fff;">'
                            . '<div class="stat-item">'
                            . '<h2 class="counter" data-target="' . $stat['number'] . '" style="color: #bc841c;">0</h2>'
                            . '<p>' . $stat['label'] . '</p>'
                            . '</div>'
                            . '</div>';
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
    <section class="testimonials-section pt-5 bg-light" style="margin-bottom: -25%;">
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
                    </div>
                </div>
            </div>
        </div>
    </section>

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
    <script>
    // Train effect for services
    document.addEventListener('DOMContentLoaded', function() {
        const train = document.getElementById('servicesTrain');
        const outer = document.querySelector('.services-train-outer');
        let scrollAmount = 0;
        let speed = 1; // px per frame
        let cardWidth = 340 + 24; // card width + margin (mx-3 = 1.5rem = 24px)
        let totalCards = train.children.length / 2;
        let maxScroll = cardWidth * totalCards;
        let paused = false;
        outer.addEventListener('mouseenter', function() { paused = true; });
        outer.addEventListener('mouseleave', function() { paused = false; });
        function animateTrain() {
            if (!paused) {
                scrollAmount += speed;
                if (scrollAmount >= maxScroll) {
                    scrollAmount = 0;
                }
                train.style.transform = `translateX(-${scrollAmount}px)`;
            }
            requestAnimationFrame(animateTrain);
        }
        animateTrain();
        });
    </script>
</body>
</html> 

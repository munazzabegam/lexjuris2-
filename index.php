<?php
$page_title = "Lawyex - Legal Services";
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
    <header class="hero-section">
        <div class="video-background">
            <video autoplay muted loop id="myVideo">
                <source src="videos/bgvideo.mp4" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </div>
        <div class="container">
            <div class="row min-vh-100 align-items-center">
                <div class="col-lg-6" data-aos="fade-right" data-aos-delay="200">
                    <div class="logo">
                        <img src="assets/images/logo11.jpg" style="width: 100px; height: 100px;" alt="Lawyex Logo" class="img-fluid rounded-circle">
                    </div>
                    <h1 class="display-4 fw-bold text-white mb-4">Professional Legal Services</h1>
                    <p class="lead text-white mb-4">We provide expert legal solutions for individuals and businesses. Our experienced team is dedicated to protecting your rights and interests.</p>
                    <?php
                    // Fetch active contact number from database
                    $query = "SELECT phone FROM contact WHERE is_active = 1 ORDER BY id DESC LIMIT 1";
                    $result = $conn->query($query);
                    $phone = "9742964416"; // Default fallback number
                    
                    if ($result && $result->num_rows > 0) {
                        $contact = $result->fetch_assoc();
                        if (!empty($contact['phone'])) {
                            $phone = $contact['phone'];
                        }
                    }
                    ?>
                    <a href="tel:<?php echo htmlspecialchars($phone); ?>" class="btn btn-warning btn-lg">
                        Contact Us
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Services Section -->
    <section class="services-section py-5">
        <div class="container">
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
                        'icon' => 'fa-balance-scale',
                        'title' => 'Family Law',
                        'description' => 'Expert guidance through divorce, custody, and family matters.'
                    ],
                    [
                        'icon' => 'fa-passport',
                        'title' => 'Immigration Law',
                        'description' => 'Comprehensive immigration services and visa assistance.'
                    ],
                    [
                        'icon' => 'fa-gavel',
                        'title' => 'Civil Law',
                        'description' => 'Professional representation in civil disputes and litigation.'
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
                ?>
            </div>
        </div>
    </section>

    <!-- Recent Cases Section -->
    <section class="recent-cases-section py-5">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-12" data-aos="fade-up">
                    <h2 class="section-title">Our Recent Cases</h2>
                    <p class="section-subtitle">Successfully resolved legal matters</p>
                </div>
            </div>
            <div class="row g-4">
                <?php
                // Fetch recent cases from database
                $cases_query = "SELECT * FROM cases ORDER BY created_at DESC LIMIT 3";
                $cases_result = $conn->query($cases_query);
                
                if ($cases_result && $cases_result->num_rows > 0) {
                    $index = 0;
                    while ($case = $cases_result->fetch_assoc()) {
                        // Format category for display
                        $category = ucwords(str_replace('_', ' ', $case['category']));
                        
                        // Get status badge color
                        $status_color = 'bg-success';
                        if ($case['status'] == 'Open') {
                            $status_color = 'bg-warning';
                        } elseif ($case['status'] == 'In Progress') {
                            $status_color = 'bg-info';
                        }
                        
                        echo '<div class="col-md-4" data-aos="fade-up" data-aos-delay="' . ($index * 200) . '">
                            <div class="case-card">
                                <div class="case-category">' . htmlspecialchars($category) . '</div>
                                <h3>' . htmlspecialchars($case['title']) . '</h3>
                                <p class="case-description">' . htmlspecialchars(substr($case['description'], 0, 100)) . '...</p>
                                <div class="case-result">
                                    <span class="badge ' . $status_color . '">' . htmlspecialchars($case['status']) . '</span>
                                    <span class="badge bg-secondary ms-2">Case #' . htmlspecialchars($case['case_number']) . '</span>
                                </div>';
                        
                        // Add tags if they exist
                        if (!empty($case['tags'])) {
                            echo '<div class="case-tags mt-2">';
                            $tags = explode(',', $case['tags']);
                            foreach ($tags as $tag) {
                                echo '<span class="badge bg-light text-dark me-1">' . htmlspecialchars(trim($tag)) . '</span>';
                            }
                            echo '</div>';
                        }
                        
                        // Add view details button
                        echo '<div class="case-footer mt-3">
                            <a href="case-details.php?id=' . $case['id'] . '" class="btn btn-outline-warning btn-sm">View Details</a>
                        </div>';
                        
                        echo '</div></div>';
                        $index++;
                    }
                } else {
                    // Fallback if no cases found in database
                    $recent_cases = [
                        [
                            'title' => 'Corporate Merger Success',
                            'category' => 'Corporate Law',
                            'description' => 'Successfully facilitated a $50M merger between two major tech companies.',
                            'result' => 'Successful Merger'
                        ],
                        [
                            'title' => 'Landmark Property Dispute',
                            'category' => 'Property Law',
                            'description' => 'Resolved complex property dispute involving multiple stakeholders.',
                            'result' => 'Favorable Settlement'
                        ],
                        [
                            'title' => 'High-Profile Divorce Case',
                            'category' => 'Family Law',
                            'description' => 'Handled sensitive divorce proceedings with amicable resolution.',
                            'result' => 'Mutual Agreement'
                        ]
                    ];

                    foreach ($recent_cases as $index => $case) {
                        echo '<div class="col-md-4" data-aos="fade-up" data-aos-delay="' . ($index * 200) . '">
                            <div class="case-card">
                                <div class="case-category">' . $case['category'] . '</div>
                                <h3>' . $case['title'] . '</h3>
                                <p class="case-description">' . substr($case['description'], 0, 100) . '...</p>
                                <div class="case-result">
                                    <span class="badge bg-success">' . $case['result'] . '</span>
                                </div>
                                <div class="case-footer mt-3">
                                    <a href="#" class="btn btn-outline-warning btn-sm">View Details</a>
                                </div>
                            </div>
                        </div>';
                    }
                }
                ?>
            </div>
        </div>
    </section>

    <style>
        .case-card {
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            height: 100%;
            transition: transform 0.3s ease;
            display: flex;
            flex-direction: column;
        }
        
        .case-card:hover {
            transform: translateY(-5px);
        }
        
        .case-category {
            color: #bc841c;
            font-weight: 600;
            margin-bottom: 10px;
            font-size: 0.9rem;
            text-transform: uppercase;
        }
        
        .case-card h3 {
            font-size: 1.1rem;
            margin-bottom: 10px;
            color: #333;
            line-height: 1.4;
        }
        
        .case-description {
            color: #666;
            margin-bottom: 15px;
            line-height: 1.5;
            font-size: 0.9rem;
            flex-grow: 1;
        }
        
        .case-result {
            margin-top: auto;
        }
        
        .case-tags {
            margin-top: 10px;
        }
        
        .case-tags .badge {
            font-size: 0.75rem;
            padding: 4px 8px;
            margin-right: 5px;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
        }

        .case-footer {
            margin-top: auto;
            padding-top: 10px;
        }

        .case-footer .btn {
            font-size: 0.85rem;
            padding: 5px 15px;
        }
    </style>

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
                        // Fetch social links for this team member
                        $social_query = "SELECT * FROM team_social_links WHERE team_id = ? AND is_active = 1";
                        $social_stmt = $conn->prepare($social_query);
                        $social_stmt->bind_param("i", $member['id']);
                        $social_stmt->execute();
                        $social_result = $social_stmt->get_result();
                        
                        echo '<div class="col-md-4 mb-4" data-aos="zoom-in" data-aos-delay="' . ($index * 100) . '">
                            <div class="main-team-card">
                                <img src="' . htmlspecialchars($member['photo']) . '" alt="' . htmlspecialchars($member['full_name']) . '" class="team-img">
                                <div class="team-info-overlay">
                                    <h3>' . htmlspecialchars($member['full_name']) . '</h3>
                                    <p>' . htmlspecialchars($member['position']) . '</p>';
                        
                        // Add social links if they exist
                        if ($social_result && $social_result->num_rows > 0) {
                            echo '<div class="team-social-links">';
                            while ($social = $social_result->fetch_assoc()) {
                                $icon_class = '';
                                switch ($social['platform']) {
                                    case 'LinkedIn':
                                        $icon_class = 'fab fa-linkedin';
                                        break;
                                    case 'Twitter':
                                        $icon_class = 'fab fa-twitter';
                                        break;
                                    case 'Email':
                                        $icon_class = 'fas fa-envelope';
                                        break;
                                    case 'Facebook':
                                        $icon_class = 'fab fa-facebook';
                                        break;
                                    case 'Instagram':
                                        $icon_class = 'fab fa-instagram';
                                        break;
                                    case 'GitHub':
                                        $icon_class = 'fab fa-github';
                                        break;
                                    default:
                                        $icon_class = 'fas fa-link';
                                }
                                echo '<a href="' . htmlspecialchars($social['url']) . '" target="_blank" class="social-link">
                                    <i class="' . $icon_class . '"></i>
                                </a>';
                            }
                            echo '</div>';
                        }
                        
                        echo '</div></div></div>';
                        $index++;
                    }
                } else {
                    // Fallback if no team members found
                    $team_members = [
                        [
                            'image' => 'https://images.unsplash.com/photo-1521737852567-6949f3f9f2b5?auto=format&fit=crop&w=400&q=80',
                            'name' => 'Omer Farooq Mulki',
                            'position' => 'B.A. (Law), LL.B',
                            'portfolio' => 'portfolio1.html'
                        ],
                        [
                            'image' => 'https://images.unsplash.com/photo-1515378791036-0648a3ef77b2?auto=format&fit=crop&w=400&q=80',
                            'name' => 'Asif Baikady',
                            'position' => 'B.Com, LL.B',
                            'portfolio' => 'portfolio2.html'
                        ],
                        [
                            'image' => 'https://images.unsplash.com/photo-1503676382389-4809596d5290?auto=format&fit=crop&q=80',
                            'name' => 'Mahammad Asgar',
                            'position' => 'B.A. (Law), LL.B',
                            'portfolio' => 'portfolio3.html'
                        ]
                    ];

                    foreach ($team_members as $index => $member) {
                        echo '<div class="col-md-4 mb-4" data-aos="zoom-in" data-aos-delay="' . ($index * 100) . '">
                            <div class="main-team-card">
                                <img src="' . $member['image'] . '" alt="' . $member['name'] . '">
                                <div class="team-info-overlay">
                                    <h3>' . $member['name'] . '</h3>
                                    <p>' . $member['position'] . '</p>
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
        .main-team-card {
            position: relative;
            overflow: hidden;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        
        .main-team-card:hover {
            transform: translateY(-5px);
        }
        
        .team-img {
            width: 100%;
            height: 350px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        
        .main-team-card:hover .team-img {
            transform: scale(1.05);
        }
        
        .team-info-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
            padding: 20px;
            color: white;
        }
        
        .team-info-overlay h3 {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 600;
        }
        
        .team-info-overlay p {
            margin: 5px 0 0;
            font-size: 0.9rem;
            opacity: 0.9;
        }
        
        .team-social-links {
            margin-top: 10px;
        }
        
        .social-link {
            color: white;
            margin-right: 10px;
            font-size: 1.1rem;
            transition: color 0.3s ease;
        }
        
        .social-link:hover {
            color: #bc841c;
        }
    </style>

    <!-- Practice Areas Section -->
    <section class="practice-area py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 data-aos="fade-up" data-aos-delay="100">Our Practice Areas</h2>
                <p data-aos="fade-up" data-aos-delay="200">Expertise across all major legal fields</p>
            </div>

            <div class="row">
                <!-- Card 1 -->
                <div class="col-md-4 mb-4" data-aos="zoom-in" data-aos-delay="100">
                    <div class="card h-100 shadow border-0">
                        <div class="card-body text-center">
                            <i class="fas fa-shield-alt display-4 text-warning mb-3"></i>
                            <h5 class="card-title">Criminal Law</h5>
                            <p class="card-text">Protecting your rights in all criminal defense matters.</p>
                        </div>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="col-md-4 mb-4" data-aos="zoom-in" data-aos-delay="200">
                    <div class="card h-100 shadow border-0">
                        <div class="card-body text-center">
                            <i class="fas fa-users display-4 text-warning mb-3"></i>
                            <h5 class="card-title">Family Law</h5>
                            <p class="card-text">Handling divorce, custody, and family disputes with care.</p>
                        </div>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="col-md-4 mb-4" data-aos="zoom-in" data-aos-delay="300">
                    <div class="card h-100 shadow border-0">
                        <div class="card-body text-center">
                            <i class="fas fa-building display-4 text-warning mb-3"></i>
                            <h5 class="card-title">Corporate Law</h5>
                            <p class="card-text">Legal support for businesses, startups, and entrepreneurs.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="testimonials-section py-5 bg-light">
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
            max-width: 800px;
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
        });
    </script>
</body>
</html> 

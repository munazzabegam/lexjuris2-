<?php
$page_title = "Lawyex - Legal Services";
$current_page = "home";
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
                    <a href="tel:9742964416" class="btn btn-warning btn-lg">Contact Us</a>
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
                            <p>' . $case['description'] . '</p>
                            <div class="case-result">
                                <span class="badge bg-success">' . $case['result'] . '</span>
                            </div>
                        </div>
                    </div>';
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
                    ],
                    [
                        'image' => 'https://images.unsplash.com/photo-1464983953574-0892a716854b?auto=format&fit=crop&w=400&q=80',
                        'name' => 'Abu Harish',
                        'position' => 'B.A. (Law), LL.B',
                        'portfolio' => 'portfolio4.html'
                    ],
                    [
                        'image' => 'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?auto=format&fit=crop&w=400&q=80',
                        'name' => 'Umarul Farook',
                        'position' => 'B.A., LL.B',
                        'portfolio' => 'portfolio5.html'
                    ],
                    [
                        'image' => 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=400&q=80',
                        'name' => 'I.M. Ijaz Ahmed Ullal',
                        'position' => 'B.A. (Law), LL.B',
                        'portfolio' => 'portfolio6.html'
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
                ?>
            </div>
            <div class="row mt-4">
                <div class="col-12 text-center" data-aos="fade-up" data-aos-delay="200">
                    <a href="our-teams.php" class="btn btn-warning btn-lg">More Teams</a>
                </div>
            </div>
        </div>
    </section>

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
                    <h2 class="section-title">Client Testimonials</h2>
                    <p class="section-subtitle">What our clients say about us</p>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="testimonial-slider">
                        <div class="testimonial-item text-center">
                            <p class="testimonial-text">"Excellent service and professional team. They helped me through a difficult divorce with compassion and expertise."</p>
                            <h4>Jane Doe</h4>
                            <p class="testimonial-role">Family Law Client</p>
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
                    <div id="teamCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <?php
                            $team_gallery_images = [
                                // 'assets/images/teampic1.jpeg',
                                // 'assets/images/teampic2.jpeg',
                                // 'assets/images/teampic3.jpeg',
                                'assets/images/teampic4.jpeg',
                                // 'assets/images/teampic5.jpeg',
                                'assets/images/teampic6.jpeg',
                                'assets/images/teampic7.jpeg',
                                'assets/images/teampic8.jpeg',
                            ];

                            foreach ($team_gallery_images as $index => $image) {
                                $active_class = ($index === 0) ? 'active' : '';
                                echo '<div class="carousel-item ' . $active_class . '">';
                                echo '<img src="' . $image . '" class="d-block w-100" alt="Team Image ' . ($index + 1) . '">';
                                echo '<div class="carousel-caption d-none d-md-block">';
                                // echo '<h5>Our Teams</h5>';
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
</body>
</html> 

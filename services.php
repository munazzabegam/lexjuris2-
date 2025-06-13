<?php
$page_title = "Our Services - Lawyex";
$current_page = "services";
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

    <!-- Services Overview -->
    <section class="services-overview py-5">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-12" data-aos="fade-up">
                    <h2 class="section-title">Legal Services We Offer</h2>
                    <p class="section-subtitle">Comprehensive legal solutions for individuals and businesses</p>
                </div>
            </div>
            <div class="row g-4">
                <?php
                $main_services = [
                    [
                        'icon' => 'fa-balance-scale',
                        'title' => 'Family Law',
                        'description' => 'Expert guidance through divorce, custody, and family matters.',
                        'features' => ['Divorce & Separation', 'Child Custody', 'Child Support', 'Alimony']
                    ],
                    [
                        'icon' => 'fa-passport',
                        'title' => 'Immigration Law',
                        'description' => 'Comprehensive immigration services and visa assistance.',
                        'features' => ['Visa Applications', 'Green Card', 'Citizenship', 'Deportation Defense']
                    ],
                    [
                        'icon' => 'fa-gavel',
                        'title' => 'Civil Law',
                        'description' => 'Professional representation in civil disputes and litigation.',
                        'features' => ['Contract Disputes', 'Property Law', 'Personal Injury', 'Employment Law']
                    ]
                ];

                foreach ($main_services as $index => $service) {
                    echo '<div class="col-md-4" data-aos="fade-up" data-aos-delay="' . ($index * 200) . '">
                        <div class="service-card">
                            <i class="fas ' . $service['icon'] . ' service-icon"></i>
                            <h3>' . $service['title'] . '</h3>
                            <p>' . $service['description'] . '</p>
                            <ul class="list-unstyled">';
                    foreach ($service['features'] as $feature) {
                        echo '<li><i class="fas fa-check text-warning me-2"></i>' . $feature . '</li>';
                    }
                    echo '</ul></div></div>';
                }
                ?>
            </div>
        </div>
    </section>

    <!-- Additional Services -->
    <section class="additional-services py-5 bg-light">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-12" data-aos="fade-up">
                    <h2 class="section-title">Additional Services</h2>
                    <p class="section-subtitle">Specialized legal solutions for your specific needs</p>
                </div>
            </div>
            <div class="row g-4">
                <?php
                $additional_services = [
                    [
                        'icon' => 'fa-building',
                        'title' => 'Business Law',
                        'description' => 'Comprehensive legal services for businesses of all sizes.',
                        'features' => ['Business Formation', 'Contract Review', 'Mergers & Acquisitions']
                    ],
                    [
                        'icon' => 'fa-home',
                        'title' => 'Real Estate Law',
                        'description' => 'Expert guidance in all real estate matters.',
                        'features' => ['Property Transactions', 'Landlord-Tenant Disputes', 'Property Development']
                    ]
                ];

                foreach ($additional_services as $index => $service) {
                    echo '<div class="col-md-6" data-aos="fade-up" data-aos-delay="' . ($index * 200) . '">
                        <div class="service-detail-card">
                            <div class="row align-items-center">
                                <div class="col-md-3 text-center">
                                    <i class="fas ' . $service['icon'] . ' service-icon"></i>
                                </div>
                                <div class="col-md-9">
                                    <h3>' . $service['title'] . '</h3>
                                    <p>' . $service['description'] . '</p>
                                    <ul class="list-unstyled">';
                    foreach ($service['features'] as $feature) {
                        echo '<li><i class="fas fa-check text-warning me-2"></i>' . $feature . '</li>';
                    }
                    echo '</ul></div></div></div></div>';
                }
                ?>
            </div>
        </div>
    </section>

    <!-- Why Choose Our Services -->
    <section class="why-choose-services py-5">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-12" data-aos="fade-up">
                    <h2 class="section-title">Why Choose Our Services</h2>
                    <p class="section-subtitle">Experience the difference with our dedicated team</p>
                </div>
            </div>
            <div class="row g-4">
                <?php
                $features = [
                    [
                        'icon' => 'fa-users',
                        'title' => 'Expert Team',
                        'description' => 'Highly qualified and experienced legal professionals dedicated to your case.'
                    ],
                    [
                        'icon' => 'fa-clock',
                        'title' => '24/7 Support',
                        'description' => 'Round-the-clock assistance for urgent legal matters and concerns.'
                    ],
                    [
                        'icon' => 'fa-handshake',
                        'title' => 'Client Focus',
                        'description' => 'Personalized attention and dedicated service for every client.'
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

    <!-- CTA Section -->
    <section class="cta-section py-5 bg-primary text-white">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8" data-aos="fade-right">
                    <h2 class="mb-4">Need Legal Assistance?</h2>
                    <p class="lead mb-0">Contact us today for a free consultation and let us help you with your legal needs.</p>
                </div>
                <div class="col-lg-4 text-lg-end" data-aos="fade-left">
                    <a href="tel:9742964416" class="btn btn-warning btn-lg">Get Started</a>
                </div>
            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="assets/js/main.js"></script>
</body>
</html> 

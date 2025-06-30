<?php
$page_title = "About Us - LexJuris";
$current_page = "about";
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

    <!-- About Section -->
    <section class="about-section py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6" data-aos="fade-right" data-aos-delay="200">
                    <img src="https://images.pexels.com/photos/6077326/pexels-photo-6077326.jpeg?auto=compress&w=1200&q=80" alt="About Us" class="img-fluid rounded">
                </div>
                <div class="col-lg-6" data-aos="fade-left" data-aos-delay="400">
                    <h2 class="section-title">Our Journey: The Evolution of Lex Juris Law Chamber</h2>
                    <p class="lead">A story of perseverance, collaboration, and an enduring pursuit of legal excellence.</p>
                    <p>The inception of Lex Juris Law Chamber traces back to three zealous legal minds — Asif, Aris, and U.F. — who established their first office near the State Bank, opposite Noufal Hotel. As their practice gained momentum, they welcomed Advocate O.F., followed by Advocate Asgar, a litigator renowned for his courtroom acumen.</p>
                    <p>Motivated by a shared vision of a more sophisticated workspace, the team embarked on a year-long search for a new office. Their perseverance bore fruit when they discovered an ideal location and transformed it into a thoughtfully designed legal chamber with six elegantly structured cabins.</p>
                    <p>With five dedicated advocates at the helm, they extended an invitation to Advocate Ijaz — a trusted friend and former classmate of Asgar — completing the circle. Thus, six advocates — united by trust, shared purpose, and an unwavering commitment to justice — inaugurated a new era of Lex Juris Law Chamber.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Mission & Vision -->
    <section class="mission-vision py-5 bg-light">
        <div class="container">
            <div class="row g-4">
                <?php
                $mission_vision = [
                    [
                        'icon' => 'fa-bullseye',
                        'title' => 'Our Mission',
                        'description' => 'To provide exceptional legal services with integrity, professionalism, and a commitment to client success.'
                    ],
                    [
                        'icon' => 'fa-eye',
                        'title' => 'Our Vision',
                        'description' => 'To be the leading law firm known for excellence, innovation, and client satisfaction in the legal industry.'
                    ]
                ];

                foreach ($mission_vision as $index => $item) {
                    echo '<div class="col-md-6" data-aos="fade-up" data-aos-delay="' . ($index * 200) . '">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body text-center p-4">
                                <i class="fas ' . $item['icon'] . ' fa-3x text-warning mb-3"></i>
                                <h3>' . $item['title'] . '</h3>
                                <p>' . $item['description'] . '</p>
                            </div>
                        </div>
                    </div>';
                }
                ?>
            </div>
        </div>
    </section>

    <!-- Values Section -->
    <section class="values-section py-5">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-12" data-aos="fade-up">
                    <h2 class="section-title">Our Core Values</h2>
                    <p class="section-subtitle">The principles that guide our practice</p>
                </div>
            </div>
            <div class="row g-4">
                <?php
                $values = [
                    [
                        'icon' => 'fa-handshake',
                        'title' => 'Integrity',
                        'description' => 'We maintain the highest standards of professional ethics and honesty in all our dealings.'
                    ],
                    [
                        'icon' => 'fa-users',
                        'title' => 'Client Focus',
                        'description' => 'We prioritize our clients\' needs and work tirelessly to achieve their objectives.'
                    ],
                    [
                        'icon' => 'fa-gavel',
                        'title' => 'Excellence',
                        'description' => 'We strive for excellence in every aspect of our legal practice and client service.'
                    ]
                ];

                foreach ($values as $index => $value) {
                    echo '<div class="col-md-4" data-aos="fade-up" data-aos-delay="' . ($index * 200) . '">
                        <div class="value-card text-center">
                            <i class="fas ' . $value['icon'] . ' fa-3x text-warning mb-3"></i>
                            <h3>' . $value['title'] . '</h3>
                            <p>' . $value['description'] . '</p>
                        </div>
                    </div>';
                }
                ?>
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
<?php
$page_title = "About Us - LexJuris";
$current_page = "about";
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


    <!-- About Section -->
    <section class="about-section py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6" data-aos="fade-right" data-aos-delay="200">
                    <img id="about-main-img" src="https://images.pexels.com/photos/6077326/pexels-photo-6077326.jpeg?auto=compress&w=1200&q=80" alt="About Us" class="img-fluid rounded w-100" style="height:600px;object-fit:cover;">
                </div>
                <div class="col-lg-6 d-flex flex-column" data-aos="fade-left" data-aos-delay="400">
                    <h2 class="section-title">Our Journey: The Evolution of Lex Juris Law Chamber</h2>
                    <div class="evolution-content" style="max-height:600px;overflow:auto;">
                        <p class="lead mb-4">The Evolution of Lex Juris Law Chamber</p>
                        <p class="mb-3">The inception of Lex Juris Law Chamber traces back to three zealous legal minds and close confidants — <strong>Adv. Asif Baikady</strong>, <strong>Adv. Abu Haris</strong> and <strong>Adv. Umarul Farooq</strong> who shared a unified dream and were unwavering in their pursuit of a common goal: to establish a distinguished and ethical legal practice.</p>
                        <p class="mb-3">Driven by ambition and the strength of their camaraderie, their shared vision took form with the inauguration of their first office near State Bank, opposite Noufal Hotel, under the moniker Lex Juris Law Chamber.</p>
                        <p class="mb-3">As their practice steadily gained momentum, they welcomed a dynamic addition to their team — <strong>Adv. Omer Farooq</strong> — whose fresh perspective and vibrant energy brought renewed vitality to the chamber. In the post-pandemic era, <strong>Adv. Asif Baikady</strong> connected with <strong>Adv. Asgar</strong>, a litigator known for his sharp legal acumen, relentless dedication, and charismatic nature. Although Adv. Asgar was not a daily presence in the beginning, his substantial contributions to the firm's legal strategies and courtroom performance added immense value.</p>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-12">
                    <div class="evolution-content-continued">
                        <p class="mb-3">Motivated by a collective desire to foster a more sophisticated and professional environment, the team embarked on a year-long search for a new office space. Their perseverance paid off when they discovered an ideal location in the heart of the city's bustling business district. The team meticulously transformed this space into a thoughtfully designed, efficient, and well-appointed legal chamber.</p>
                        <p class="mb-3">The newly established office, featuring six elegantly structured cabins, laid the foundation for the firm's next chapter. With five dedicated advocates already leading the charge, they extended an invitation to <strong>Adv. Ijaz</strong> a trusted friend and former classmate of Adv. Asgar who enthusiastically joined the team, completing the circle.</p>
                        <p class="mb-3">Thus, six advocates, united by trust, shared purpose, and an unwavering commitment to justice, inaugurated a new era for Lex Juris Law Chamber.</p>
                        <p class="mb-3">Their story is one of perseverance, collaboration, and an enduring pursuit of legal excellence.</p>
                        <p class="mb-0">Today, Lex Juris stands as a pillar of legal advocacy in Mangaluru, offering comprehensive legal assistance to all segments of society. With a team that blends youthful dynamism and seasoned experience, the chamber has grown into one of the most prominent and respected law firms in the region, driven by a relentless commitment to justice, ethics, and service.</p>
                            </div>
                        </div>
            </div>
        </div>
    </section>

 


    <!-- Our Services Section -->
    <section class="services-section py-5 bg-light">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-12" data-aos="fade-up">
                    <h2 class="section-title">Our Services</h2>
                    <p class="section-subtitle">Comprehensive legal solutions for all your needs</p>
                </div>
            </div>
            <div class="row justify-content-center">
                <?php
                $services = [
                    [
                        'img' => 'assets/images/image1.jpg',
                        'svg' => 'assets/images/icons/gavel.svg',
                        'title' => 'Civil Law',
                        'desc' => 'Private disputes between individuals or organizations, including contracts, torts, property, and family law.',
                        'link' => '#'
                    ],
                    [
                        'img' => 'assets/images/image1.jpg',
                        'svg' => 'assets/images/icons/support.svg',
                        'title' => 'Criminal Law',
                        'desc' => 'Prosecution of crimes against society, including theft, assault, cybercrime, and more.',
                        'link' => '#'
                    ],
                    [
                        'img' => 'assets/images/image1.jpg',
                        'svg' => 'assets/images/icons/team.svg',
                        'title' => 'Family Law',
                        'desc' => 'Matters such as marriage, divorce, child custody, maintenance, and domestic violence.',
                        'link' => '#'
                    ],
                ];
                foreach ($services as $index => $s) {
                    echo '<div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="' . ($index * 100) . '">';
                    echo '<div class="service-modern-card position-relative bg-white rounded-4 shadow-sm p-4 h-100">';
                    echo '<div class="service-img-wrap position-relative">';
                    echo '<img src="' . $s['img'] . '" class="img-fluid rounded-3 w-100" style="height:220px;object-fit:cover;">';
                    echo '<span class="service-modern-icon position-absolute top-100 start-0 translate-middle-y bg-white rounded-3 shadow d-flex align-items-center justify-content-center" style="width:56px;height:56px;left:24px;top:180px;">';
                    echo str_replace('<svg', '<svg width="35" height="35"', file_get_contents($s['svg']));
                    echo '</span>';
                    echo '</div>';
                    echo '<div class="pt-4 position-relative" style="margin-top:5%;">';
                    echo '<h5 class="fw-bold mb-2" style="font-family:Playfair Display,serif;font-size:1.5rem;">' . $s['title'] . '</h5>';
                    echo '<p class="mb-4 text-muted">' . $s['desc'] . '</p>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
                ?>
            </div>
            <div class="row">
                <div class="col-12 text-center mt-3">
                    <a href="services.php" class="btn btn-warning btn-lg px-5">View More</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="testimonials-section pt-5 bg-light" style="padding-bottom: 3rem;">
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

        <!-- contact Section -->
        <section class="contact-hero-section d-flex align-items-center justify-content-center" style="position: relative; height: 350px; background: url('https://images.pexels.com/photos/618613/pexels-photo-618613.jpeg?auto=compress&w=1500&q=80') center center/cover no-repeat;">
            <div class="contact-hero-overlay" style="position: absolute; top:0; left:0; width:100%; height:100%; background: rgba(20, 30, 40, 0.65); z-index:1;"></div>
            <div class="container position-relative text-center" style="z-index:2;">
                <div class="row justify-content-center">
                    <div class="col-lg-8" data-aos="zoom-in" data-aos-delay="100">
                        <span class="text-uppercase text-white fw-bold" style="letter-spacing:1px; font-size:1rem;">Legal Services</span>
                        <h2 class="section-title text-white my-2" style="font-size:2.2rem;">We help solve your legal issues</h2>
                        <a href="#contact" class="btn btn-warning px-4 py-2 fw-semibold mt-3" style="font-size:1rem;">Make an Appointment</a>
                    </div>
                </div>
            </div>
        </section>



    <?php include 'includes/footer.php'; ?>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="assets/js/main.js"></script>
    <!-- Before </body> tag, ensure AOS is included and initialized -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" />
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script>
      AOS.init();
    </script>
</body>
</html> 
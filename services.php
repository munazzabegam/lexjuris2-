<?php
$page_title = "Our Services - Lex Juris";
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
            <div class="row g-4" id="servicesGrid">
                <?php
                $services = [
                    [
                        'img' => 'assets/images/image1.jpg',
                        'svg' => 'assets/images/icons/team.svg',
                        'title' => 'Civil Law',
                        'desc' => 'Deals with private disputes between individuals or organizations. Includes contract law, tort law, property law, and family law.',
                        'link' => '#'
                    ],
                    [
                        'img' => 'assets/images/image1.jpg',
                        'svg' => 'assets/images/icons/support.svg',
                        'title' => 'Criminal Law',
                        'desc' => 'Involves prosecution by the state of wrongful acts against society. Includes theft, assault, murder, and cybercrime.',
                        'link' => '#'
                    ],
                    [
                        'img' => 'assets/images/image1.jpg',
                        'svg' => 'assets/images/icons/focus.svg',
                        'title' => 'Family Law',
                        'desc' => 'Covers matters such as marriage, divorce, child custody, maintenance, adoption, and domestic violence.',
                        'link' => '#'
                    ],
                    [
                        'img' => 'assets/images/image1.jpg',
                        'svg' => 'assets/images/icons/gavel.svg',
                        'title' => 'Corporate/Business Law',
                        'desc' => 'Regulates the formation, operation, and dissolution of businesses. Includes mergers & acquisitions and corporate governance.',
                        'link' => '#'
                    ],
                    [
                        'img' => 'assets/images/image1.jpg',
                        'svg' => 'assets/images/icons/law.svg',
                        'title' => 'Labour & Employment Law',
                        'desc' => 'Governs employer-employee relationships. Covers wages, termination, discrimination, and workplace safety.',
                        'link' => '#'
                    ],
                    [
                        'img' => 'assets/images/image1.jpg',
                        'svg' => 'assets/images/icons/mission.svg',
                        'title' => 'Property / Real Estate Law',
                        'desc' => 'Deals with rights and duties related to real property. Includes transactions, leasing, zoning, and landlord-tenant disputes.',
                        'link' => '#'
                    ],
                    [
                        'img' => 'assets/images/image1.jpg',
                        'svg' => 'assets/images/icons/vision.svg',
                        'title' => 'Constitutional Law',
                        'desc' => 'Involves interpretation and application of the Constitution. Covers fundamental rights, duties, and judicial review.',
                        'link' => '#'
                    ],
                    [
                        'img' => 'assets/images/image1.jpg',
                        'svg' => 'assets/images/icons/court.svg',
                        'title' => 'Administrative Law',
                        'desc' => 'Governs the actions and operations of government agencies. Includes licensing, regulation, and tribunal procedures.',
                        'link' => '#'
                    ],
                    [
                        'img' => 'assets/images/image1.jpg',
                        'svg' => 'assets/images/icons/focus.svg',
                        'title' => 'Tax Law',
                        'desc' => 'Involves the assessment and collection of taxes. Includes tax planning, appeals, and litigation.',
                        'link' => '#'
                    ],
                    [
                        'img' => 'assets/images/image1.jpg',
                        'svg' => 'assets/images/icons/support.svg',
                        'title' => 'Environmental Law',
                        'desc' => 'Deals with protection of the environment and natural resources. Includes pollution control and forest conservation.',
                        'link' => '#'
                    ],
                    [
                        'img' => 'assets/images/image1.jpg',
                        'svg' => 'assets/images/icons/gavel.svg',
                        'title' => 'Intellectual Property (IP) Law',
                        'desc' => 'Protects creations of the mind such as inventions, trademarks, copyrights, and patents.',
                        'link' => '#'
                    ],
                    [
                        'img' => 'assets/images/image1.jpg',
                        'svg' => 'assets/images/icons/law.svg',
                        'title' => 'Cyber Law / IT Law',
                        'desc' => 'Governs digital transactions, data protection, online defamation, cybercrimes, and e-contracts.',
                        'link' => '#'
                    ],
                    [
                        'img' => 'assets/images/image1.jpg',
                        'svg' => 'assets/images/icons/mission.svg',
                        'title' => 'Consumer Protection Law',
                        'desc' => 'Provides remedies for consumers against unfair trade practices or defective goods/services.',
                        'link' => '#'
                    ],
                    [
                        'img' => 'assets/images/image1.jpg',
                        'svg' => 'assets/images/icons/vision.svg',
                        'title' => 'Human Rights Law',
                        'desc' => "Protects individuals' rights and freedoms, often involving issues like discrimination and unlawful detention.",
                        'link' => '#'
                    ],
                    [
                        'img' => 'assets/images/image1.jpg',
                        'svg' => 'assets/images/icons/court.svg',
                        'title' => 'Banking and Finance Law',
                        'desc' => 'Involves regulations related to loans, securities, financial institutions, and debt recovery.',
                        'link' => '#'
                    ],
                    [
                        'img' => 'assets/images/image1.jpg',
                        'svg' => 'assets/images/icons/team.svg',
                        'title' => 'Alternative Dispute Resolution (ADR)',
                        'desc' => 'Methods like arbitration, mediation, and conciliation to resolve disputes outside court.',
                        'link' => '#'
                    ],
                    [
                        'img' => 'assets/images/image1.jpg',
                        'svg' => 'assets/images/icons/support.svg',
                        'title' => 'International Law',
                        'desc' => 'Governs relations between nations; includes treaties, international trade, human rights, and conflict resolution.',
                        'link' => '#'
                    ]
                ];
                $total_services = count($services);
                foreach ($services as $i => $s) {
                    $extra = $i >= 6 ? ' extra-service d-none' : '';
                    echo '<div class="col-lg-4 col-md-6'.$extra.'">'
                        . '<div class="service-modern-card position-relative bg-white rounded-4 shadow-sm p-4 h-100">'
                        . '<div class="service-img-wrap position-relative">'
                        . '<img src="' . $s['img'] . '" class="img-fluid rounded-3 w-100" style="height:220px;object-fit:cover;">'
                        . '<span class="service-modern-icon position-absolute top-100 start-0 translate-middle-y bg-white rounded-3 shadow d-flex align-items-center justify-content-center" style="width:56px;height:56px;left:24px;top:180px;">'
                        . str_replace('<svg', '<svg width="35" height="35"', file_get_contents($s['svg']))
                        . '</span>'
                        . '</div>'
                        . '<div class="pt-4 position-relative" style="margin-top:5%;">'
                        . '<h5 class="fw-bold mb-2" style="font-family:Playfair Display,serif;font-size:1.5rem;">' . $s['title'] . '</h5>'
                        . '<p class="mb-4 text-muted">' . $s['desc'] . '</p>'
                        . '</div>'
                        . '</div>'
                        . '</div>';
                }
                ?>
            </div>
            <?php if ($total_services > 6): ?>
            <div class="text-center mt-4">
                <button id="viewMoreBtn" class="btn btn-warning px-4 py-2">View More</button>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Why Choose Our Services -->
    <section class="why-choose-us py-5 position-relative" style="background: url('assets/images/image2.jpg') center center/cover no-repeat;height: 80vh;">
        <div class="why-choose-overlay position-absolute w-100 h-100" style="top:0;left:0;background:rgba(0,0,0,0.7);z-index:1;"></div>
        <div class="container position-relative" style="z-index:2;">
            <div class="row text-center mb-5">
                <div class="col-12" data-aos="fade-up">
                    <h2 class="section-title text-white" style="padding-top: 2%;">Why Choose Us</h2>
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
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var btn = document.getElementById('viewMoreBtn');
        if (btn) {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.extra-service').forEach(function(el) {
                    el.classList.remove('d-none');
                });
                btn.style.display = 'none';
            });
        }
    });
    </script>
</body>
</html> 

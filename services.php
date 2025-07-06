<?php
$page_title = "Our Services - Lex Juris";
$current_page = "services";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Legal Services by LexJuris - Best advocate in Mangalore offering civil law, criminal law, family law, corporate law, property law, and more. Expert legal consultation in Karnataka.">
    <meta name="keywords" content="legal services mangalore, civil law advocate, criminal law lawyer, family law attorney, corporate law firm, property law expert, legal consultation karnataka, best lawyer mangalore">
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
    <style>
        html, body {
            max-width: 100vw;
            overflow-x: hidden;
        }
        body {
            font-family: 'Roboto', sans-serif;
        }
        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }
        .section-subtitle {
            font-size: 1.1rem;
            color: #6c757d;
        }
        .service-modern-card {
            transition: all 0.3s ease;
            border: none;
            overflow: hidden;
            min-width: 0;
            max-width: 100%;
            width: 100%;
            margin-bottom: 1.5rem;
        }
        .service-modern-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .service-modern-icon {
            margin-bottom: 20px;
        }
        .service-modern-icon svg {
            width: 32px;
            height: 32px;
            display: block;
            transition: fill 0.2s, transform 0.2s;
            fill: #000 !important;
        }
        .service-modern-icon svg * {
            fill: #000 !important;
        }
        .service-modern-card:hover .service-modern-icon {
            background: #bc841c !important;
        }
        .service-modern-card:hover .service-modern-icon svg {
            fill: rgb(255, 255, 255) !important;
            transform: scale(1.18);
        }
        .service-modern-card:hover .service-modern-icon svg * {
            fill: rgb(255, 255, 255) !important;
        }
        .feature-card {
            text-align: center;
            padding: 2rem 1rem;
        }
        .feature-card h3 {
            margin: 1rem 0;
            font-weight: 600;
        }
        .feature-card p {
            margin: 0;
            opacity: 0.9;
        }
        .feature-svg-icon svg {
            width: 100%;
            height: 100%;
            fill: #bc841c !important;
        }
        .feature-svg-icon svg * {
            fill: #bc841c !important;
        }
        /* Responsive Tweaks */
        @media (max-width: 991.98px) {
            .section-title {
                font-size: 2rem;
            }
            .service-modern-card {
                margin-bottom: 1.2rem;
            }
        }
        @media (max-width: 767.98px) {
            .section-title {
                font-size: 1.5rem;
            }
            .section-subtitle {
                font-size: 1rem;
            }
            .service-modern-card {
                padding: 1rem 0.5rem;
                margin-bottom: 1rem;
            }
            .service-img-wrap img {
                height: 160px !important;
            }
            .feature-card {
                padding: 1.2rem 0.5rem;
            }
            .why-choose-us {
                height: auto !important;
                padding-bottom: 2rem;
            }
            .why-choose-overlay {
                min-height: 0 !important;
            }
            .cta-section .row {
                flex-direction: column;
                text-align: center;
            }
            .cta-section .col-lg-8, .cta-section .col-lg-4 {
                text-align: center !important;
            }
            .cta-section h2 {
                font-size: 1.3rem;
            }
        }
        @media (max-width: 575.98px) {
            .service-modern-card {
                padding: 0.7rem 0.2rem;
            }
            .service-img-wrap img {
                height: 120px !important;
            }
            .feature-card {
                padding: 0.7rem 0.2rem;
            }
        }
        /* Ensure grid columns stack on mobile */
        @media (max-width: 991.98px) {
            #servicesGrid .col-lg-4 {
                flex: 0 0 50%;
                max-width: 50%;
            }
        }
        @media (max-width: 767.98px) {
            #servicesGrid .col-lg-4, #servicesGrid .col-md-6 {
                flex: 0 0 100%;
                max-width: 100%;
            }
        }
        /* Button spacing */
        #viewMoreBtn {
            margin-top: 1rem;
            margin-bottom: 1rem;
            width: 100%;
            max-width: 320px;
        }
    </style>
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
                        'img' => 'assets/images/card_images/civil_law_card.jpg',
                        'svg' => 'assets/images/icons/gavel.svg',
                        'title' => 'Civil Law',
                        'desc' => 'Deals with private disputes between individuals or organizations. Includes contract law, tort law, property law, and family law.',
                        'link' => '#'
                    ],
                    [
                        'img' => 'assets/images/card_images/criminal_law_card.jpeg',
                        'svg' => 'assets/images/icons/gavel.svg',
                        'title' => 'Criminal Law',
                        'desc' => 'Involves prosecution by the state of wrongful acts against society. Includes theft, assault, murder, and cybercrime.',
                        'link' => '#'
                    ],
                    [
                        'img' => 'assets/images/card_images/family-law.jpg',
                        'svg' => 'assets/images/icons/gavel.svg',
                        'title' => 'Family Law',
                        'desc' => 'Covers matters such as marriage, divorce, child custody, maintenance, adoption, and domestic violence.',
                        'link' => '#'
                    ],
                    [
                        'img' => 'assets/images/card_images/Administrative-Law.jpg',
                        'svg' => 'assets/images/icons/gavel.svg',
                        'title' => 'Corporate/Business Law',
                        'desc' => 'Regulates the formation, operation, and dissolution of businesses. Includes mergers & acquisitions and corporate governance.',
                        'link' => '#'
                    ],
                    [
                        'img' => 'assets/images/card_images/soundblock-Wood-scales-books-stack-background-leather.webp',
                        'svg' => 'assets/images/icons/gavel.svg',
                        'title' => 'Labour & Employment Law',
                        'desc' => 'Governs employer-employee relationships. Covers wages, termination, discrimination, and workplace safety.',
                        'link' => '#'
                    ],
                    [
                        'img' => 'assets/images/card_images/real_estate_law.jpeg',
                        'svg' => 'assets/images/icons/gavel.svg',
                        'title' => 'Property / Real Estate Law',
                        'desc' => 'Deals with rights and duties related to real property. Includes transactions, leasing, zoning, and landlord-tenant disputes.',
                        'link' => '#'
                    ],
                    [
                        'img' => 'assets/images/card_images/constitutional_law.jpeg',
                        'svg' => 'assets/images/icons/gavel.svg',
                        'title' => 'Constitutional Law',
                        'desc' => 'Involves interpretation and application of the Constitution. Covers fundamental rights, duties, and judicial review.',
                        'link' => '#'
                    ],
                    [
                        'img' => 'assets/images/card_images/Administrative-Law.jpg',
                        'svg' => 'assets/images/icons/gavel.svg',
                        'title' => 'Administrative Law',
                        'desc' => 'Governs the actions and operations of government agencies. Includes licensing, regulation, and tribunal procedures.',
                        'link' => '#'
                    ],
                    [
                        'img' => 'assets/images/card_images/tax_law.jpg',
                        'svg' => 'assets/images/icons/gavel.svg',
                        'title' => 'Tax Law',
                        'desc' => 'Involves the assessment and collection of taxes. Includes tax planning, appeals, and litigation.',
                        'link' => '#'
                    ],
                    [
                        'img' => 'assets/images/card_images/environmental_law.jpg',
                        'svg' => 'assets/images/icons/gavel.svg',
                        'title' => 'Environmental Law',
                        'desc' => 'Deals with protection of the environment and natural resources. Includes pollution control and forest conservation.',
                        'link' => '#'
                    ],
                    [
                        'img' => 'assets/images/card_images/Intellectual-property.jpg',
                        'svg' => 'assets/images/icons/gavel.svg',
                        'title' => 'Intellectual Property (IP) Law',
                        'desc' => 'Protects creations of the mind such as inventions, trademarks, copyrights, and patents.',
                        'link' => '#'
                    ],
                    [
                        'img' => 'assets/images/card_images/cyber_law.jpg',
                        'svg' => 'assets/images/icons/gavel.svg',
                        'title' => 'Cyber Law / IT Law',
                        'desc' => 'Governs digital transactions, data protection, online defamation, cybercrimes, and e-contracts.',
                        'link' => '#'
                    ],
                    [
                        'img' => 'assets/images/card_images/consumer_law.jpg',
                        'svg' => 'assets/images/icons/gavel.svg',
                        'title' => 'Consumer Protection Law',
                        'desc' => 'Provides remedies for consumers against unfair trade practices or defective goods/services.',
                        'link' => '#'
                    ],
                    [
                        'img' => 'assets/images/card_images/Blog photos_human rights lawyers.jpg',
                        'svg' => 'assets/images/icons/gavel.svg',
                        'title' => 'Human Rights Law',
                        'desc' => "Protects individuals' rights and freedoms, often involving issues like discrimination and unlawful detention.",
                        'link' => '#'
                    ],
                    [
                        'img' => 'assets/images/card_images/Finance_law.webp',
                        'svg' => 'assets/images/icons/gavel.svg',
                        'title' => 'Banking and Finance Law',
                        'desc' => 'Involves regulations related to loans, securities, financial institutions, and debt recovery.',
                        'link' => '#'
                    ],
                    [
                        'img' => 'assets/images/card_images/ADR.jpg',
                        'svg' => 'assets/images/icons/gavel.svg',
                        'title' => 'Alternative Dispute Resolution (ADR)',
                        'desc' => 'Methods like arbitration, mediation, and conciliation to resolve disputes outside court.',
                        'link' => '#'
                    ],
                    [
                        'img' => 'assets/images/card_images/international-law-.jpg',
                        'svg' => 'assets/images/icons/gavel.svg',
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
                        . '<h5 class="fw-bold mb-2" style="font-size:1.5rem;">' . $s['title'] . '</h5>'
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
                    <a href="tel:9555552545" class="btn btn-warning btn-lg">Get Started</a>
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
        // Back to top button functionality
        var backToTopBtn = document.getElementById('backToTopBtn');
        window.addEventListener('scroll', function() {
            if (window.scrollY > 300) {
                backToTopBtn.style.display = 'flex';
            } else {
                backToTopBtn.style.display = 'none';
            }
        });
        backToTopBtn.addEventListener('click', function() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
        // Hide initially
        backToTopBtn.style.display = 'none';
    });
    </script>
</body>
</html> 

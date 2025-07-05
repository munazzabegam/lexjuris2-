<!-- Footer -->
<footer class="site-footer">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-12 mb-4 mb-lg-0">
                <div class="footer-brand mb-3">
                    <a href="index.php" class="text-decoration-none d-inline-block">
                        <img src="assets/images/footer_logo.png" alt="Lex Juris Law Chamber" style="max-height: 80px;">
                    </a>
                </div>
                <p class="footer-description">Professional legal services for individuals and businesses. We're here to protect your rights and interests.</p>
                <div class="social-links-footer mt-4">
                    <?php
                    require_once __DIR__ . '/../config/database.php';
                    $social_links = [];
                    $result = $conn->query("SELECT * FROM social_links WHERE is_active = 1 ORDER BY order_index ASC");
                    if ($result) {
                        $social_links = $result->fetch_all(MYSQLI_ASSOC);
                    }
                    ?>
                    <?php foreach ($social_links as $link): ?>
                        <a href="<?php echo htmlspecialchars($link['url']); ?>" class="social-icon" target="_blank" aria-label="<?php echo htmlspecialchars($link['platform']); ?>">
                            <i class="fab fa-<?php echo strtolower($link['platform']); ?>"></i>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <?php
            // Split nav items for two columns
            $total_items = count($nav_items);
            $half_point = (int)ceil($total_items / 2);
            $nav_col1 = array_slice($nav_items, 0, $half_point);
            $nav_col2 = array_slice($nav_items, $half_point);
            ?>

            <div class="col-lg-2 col-md-3 col-6 mb-4 mb-lg-0">
                <h4 class="footer-title">Explore</h4>
                <ul class="footer-links-list">
                    <?php foreach ($nav_col1 as $item): ?>
                        <li><a href="<?php echo $item['url']; ?>"><i class="fas fa-angle-right"></i> <?php echo $item['text']; ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="col-lg-2 col-md-3 col-6 mb-4 mb-lg-0">
                <h4 class="footer-title" style="visibility: hidden;">More</h4> <!-- Hidden title for alignment -->
                <ul class="footer-links-list">
                    <?php foreach ($nav_col2 as $item): ?>
                        <li><a href="<?php echo $item['url']; ?>"><i class="fas fa-angle-right"></i> <?php echo $item['text']; ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
                <h4 class="footer-title">Contact</h4>
                <ul class="footer-contact-list">
                    <li>
                        <i class="fas fa-map-marker-alt"></i>
                        <span>
                            <strong>Main Branch:</strong><br>
                            6th Floor Paradigm Plaza, AB Shetty Circle, Mangalore, D.K
                        </span>
                    </li>
                    <li>
                        <i class="fas fa-map-marker-alt"></i>
                        <span>
                            <strong>Branch:</strong><br>
                            3rd Floor Canara Tower,  Mission Hospital Road, Udupi
                        </span>
                    </li>
                    <li>
                        <i class="fas fa-map-marker-alt"></i>
                        <span>
                            <strong>Bangalore:</strong><br>
                            Bangalore, Karnataka
                        </span>
                    </li>
                    <li><i class="fas fa-phone"></i>+91 7411448378, +91 9555552545</li>
                    <li><i class="fas fa-envelope"></i>teamlexjuris@gmail.com</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container text-center">
            <p class="mb-0">
                &copy; <?php echo date('Y'); ?> LexJuris. All Rights Reserved.
                <span class="developed-by">
                    | Developed by
                    <a href="https://thebrandweave.com" target="_blank" class="dev-logo-link" aria-label="The Brand Weave">
                        <img src="assets/images/brandweave_logo1.png" alt="The Brand Weave" class="dev-logo">
                    </a>
                </span>
            </p>
        </div>
    </div>
</footer>

<style>
.site-footer {
    background-color: #1a1a1a;
    color: #adb5bd;
    padding: 60px 0 0 0;
    font-size: 0.9rem;
}
.footer-brand .h2 {
    margin: 0;
}
.footer-description {
    margin-top: 15px;
    line-height: 1.7;
}
.footer-title {
    color: #ffffff;
    font-size: 1rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 20px;
}
.footer-links-list, .footer-contact-list {
    list-style: none;
    padding: 0;
}
.footer-links-list li, .footer-contact-list li {
    margin-bottom: 12px;
}
.footer-links-list a {
    color: #adb5bd;
    text-decoration: none;
    transition: all 0.3s ease;
}
.footer-links-list a:hover {
    color: #ffffff;
    transform: translateX(5px);
    display: inline-block;
}
.footer-links-list a i {
    color: #bc841c;
    margin-right: 8px;
}
.footer-contact-list li {
    display: flex;
    align-items: flex-start;
    margin-bottom: 14px;
}
.footer-contact-list i {
    color: #bc841c;
    margin-right: 12px;
    margin-top: 3px;
    font-size: 1.1rem;
    min-width: 20px;
}
.footer-contact-list span {
    display: block;
    line-height: 1.5;
}
.footer-contact-list strong {
    color: #fff;
    font-weight: 600;
    font-size: 1rem;
    letter-spacing: 0.2px;
}
.social-links-footer .social-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 38px;
    height: 38px;
    border-radius: 50%;
    background-color: rgba(255, 255, 255, 0.05);
    color: #ffffff;
    margin-right: 8px;
    transition: all 0.3s ease;
    text-decoration: none;
}
.social-links-footer .social-icon:hover {
    background-color: #bc841c;
    color: #1a1a1a;
    transform: translateY(-2px);
}
.footer-bottom {
    background-color: #111111;
    padding: 20px 0;
    margin-top: 40px;
    font-size: 0.85rem;
}
.back-to-top, .whatsapp-float {
    right: 20px !important;
    width: 45px;
    height: 45px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0;
    margin: 0;
}
.whatsapp-float {
    position: fixed;
    bottom: 75px;
    z-index: 1050;
    background: rgba(188, 132, 28, 0.18);
    color: #bc841c;
    backdrop-filter: blur(4px);
    border-radius: 50%;
    font-size: 18px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    transition: background 0.3s, transform 0.3s;
    text-decoration: none;
}
.whatsapp-float:hover {
    color:white;
    border: 1.5px solid #00000000;
    background-color: var(--primary-color);
    transform: translateY(-3px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
}
.back-to-top {
    position: fixed;
    bottom: 20px;
    z-index: 1050;
    background: rgba(188, 132, 28, 0.18);
    color: #bc841c;
    backdrop-filter: blur(4px);
    border: none;
    cursor: pointer;
    border-radius: 50%;
    font-size: 18px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    transition: all 0.3s ease;
    opacity: 0;
    transform: translateY(20px);
}

.back-to-top:hover {
    color: white;
    background-color: var(--primary-color);
    transform: translateY(-3px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
}
@media (max-width: 768px) {
    .back-to-top, .whatsapp-float {
        right: 15px !important;
        width: 40px;
        height: 40px;
        font-size: 16px;
    }
    .back-to-top {
        bottom: 15px;
    }
    .whatsapp-float {
        bottom: 55px;
    }
}
@media (max-width: 480px) {
    .back-to-top, .whatsapp-float {
        right: 10px !important;
        width: 35px;
        height: 35px;
        font-size: 14px;
    }
    .back-to-top {
        bottom: 10px;
    }
    .whatsapp-float {
        bottom: 45px;
    }
}
.developed-by {
    font-size: 0.95em;
    margin-left: 10px;
    vertical-align: middle;
}
.dev-logo-link {
    display: inline-block;
    vertical-align: middle;
    margin-left: 4px;
}
.dev-logo {
    height: 30px;
    width: auto;
    vertical-align: middle;
    filter: grayscale(0.2) brightness(0.95);
    transition: filter 0.3s;
}
.dev-logo-link:hover .dev-logo {
    filter: none;
}
</style>

<!-- Back to Top Button -->
<button id="backToTop" class="back-to-top" aria-label="Back to top">
    <i class="fas fa-arrow-up"></i>
</button>

<!-- WhatsApp Floating Button -->
<a href="https://wa.me/9555552545" class="whatsapp-float" target="_blank" aria-label="Chat on WhatsApp">
    <i class="fab fa-whatsapp"></i>
</a>

<!-- Bootstrap 5 JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Custom JS -->
<script src="assets/js/main.js"></script>

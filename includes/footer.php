<!-- Footer -->
<footer class="footer bg-dark text-white py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="footer-logo mb-3">
                    <img src="assets/images/logo.png" alt="Lex Juris Logo" class="img-fluid" style="max-height: 60px;">
                </div>
                <p>Professional legal services for individuals and businesses. We're here to protect your rights and interests at Lex Juris.</p>
                <div class="social-links">
                    <a href="#" class="text-white me-3"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="text-white me-3"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-white me-3"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#" class="text-white"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
            <div class="col-md-4">
                <h4 class="mb-3">Quick Links</h4>
                <ul class="list-unstyled">
                    <?php foreach ($nav_items as $item): ?>
                        <li class="mb-2"><a href="<?php echo $item['url']; ?>" class="text-white text-decoration-none"><?php echo $item['text']; ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="col-md-4">
                <h4 class="mb-3">Contact Info</h4>
                <ul class="list-unstyled">
                    <li class="mb-2"><i class="fas fa-map-marker-alt me-2"></i> 123 Legal Street, City, Country</li>
                    <li class="mb-2"><i class="fas fa-phone me-2"></i> +1 234 567 890</li>
                    <li class="mb-2"><i class="fas fa-envelope me-2"></i> info@lawyex.com</li>
                </ul>
            </div>
        </div>
    </div>
</footer>

<!-- Back to Top Button -->
<button id="backToTop" class="back-to-top" aria-label="Back to top">
    <i class="fas fa-arrow-up"></i>
</button>

<!-- Bootstrap 5 JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Custom JS -->
<script src="assets/js/main.js"></script>

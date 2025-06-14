// Navbar scroll behavior
window.addEventListener('scroll', function() {
    const navbar = document.querySelector('.navbar');
    if (window.scrollY > 50) {
        navbar.style.padding = '0.5rem 0';
        navbar.style.backgroundColor = '#ffffff';
        navbar.style.boxShadow = '0 4px 8px rgba(0,0,0,0.15)';
    } else {
        navbar.style.padding = '1rem 0';
        navbar.style.backgroundColor = '#ffffff';
        navbar.style.boxShadow = '0 2px 4px rgba(0,0,0,0.1)';
    }
});

// --- Animated Counter for Stats Section ---
function animateCounter(element, duration = 2000) {
    const target = parseInt(element.getAttribute('data-target'));
    let start = 0;
    const startTime = performance.now();

    function updateCounter(currentTime) {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);
        const value = Math.floor(progress * target);
        element.textContent = value;
        if (progress < 1) {
            requestAnimationFrame(updateCounter);
        } else {
            element.textContent = target;
        }
    }
    requestAnimationFrame(updateCounter);
}

// Initialize all animations and observers
document.addEventListener('DOMContentLoaded', function() {
    // Initialize all DOM elements
    const elements = {
        statsSection: document.querySelector('.stats-section'),
        backToTopButton: document.getElementById('backToTop'),
        navbarToggler: document.querySelector('.navbar-toggler'),
        navbarCollapse: document.querySelector('.navbar-collapse'),
        contactForm: document.querySelector('#contactForm'),
        scrollElements: document.querySelectorAll('.scroll-reveal'),
        cards: document.querySelectorAll('.service-card, .feature-card, .team-card, .case-card'),
        sections: document.querySelectorAll('section')
    };

    // Stats Section Animation
    if (elements.statsSection) {
        let statsAnimated = false;
        const statObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && !statsAnimated) {
                    const counters = entry.target.querySelectorAll('.counter');
                    counters.forEach(counter => {
                        animateCounter(counter, 1800);
                    });
                    statsAnimated = true;
                }
            });
        }, { threshold: 0.5 });
        statObserver.observe(elements.statsSection);
    }

    // Back to Top Button
    if (elements.backToTopButton) {
        let scrollTimeout;
        
        window.addEventListener('scroll', function() {
            // Clear the timeout if it exists
            if (scrollTimeout) {
                clearTimeout(scrollTimeout);
            }
            
            // Set a new timeout
            scrollTimeout = setTimeout(function() {
                if (window.pageYOffset > 300) {
                    elements.backToTopButton.classList.add('visible');
                } else {
                    elements.backToTopButton.classList.remove('visible');
                }
            }, 100); // Debounce the scroll event
        });

        elements.backToTopButton.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }

    // Section Title Animations
    document.querySelectorAll('.section-title, .section-subtitle').forEach((el, i) => {
        el.style.opacity = 0;
        el.style.transform = 'translateY(30px)';
        el.style.transition = 'all 0.8s cubic-bezier(0.4,0,0.2,1)';
    });

    const titleObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = 1;
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, { threshold: 0.2 });

    document.querySelectorAll('.section-title, .section-subtitle').forEach(el => {
        titleObserver.observe(el);
    });

    // Mobile Menu
    if (elements.navbarToggler && elements.navbarCollapse) {
        elements.navbarToggler.addEventListener('click', () => {
            elements.navbarCollapse.classList.toggle('show');
        });

        document.addEventListener('click', (e) => {
            if (!elements.navbarToggler.contains(e.target) && !elements.navbarCollapse.contains(e.target)) {
                elements.navbarCollapse.classList.remove('show');
            }
        });
    }

    // Form Validation
    if (elements.contactForm) {
        elements.contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const name = this.querySelector('#name').value;
            const email = this.querySelector('#email').value;
            const message = this.querySelector('#message').value;
            
            if (!name || !email || !message) {
                alert('Please fill in all fields');
                return;
            }
            
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                alert('Please enter a valid email address');
                return;
            }
            
            alert('Thank you for your message! We will get back to you soon.');
            this.reset();
        });
    }

    // Enhanced Scroll Animations
    elements.sections.forEach(section => {
        section.classList.add('scroll-reveal');
    });

    const observerOptions = {
        root: null,
        rootMargin: '0px',
        threshold: 0.15
    };

    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                
                const cards = entry.target.querySelectorAll('.service-card, .feature-card, .team-card, .case-card');
                cards.forEach((card, index) => {
                    setTimeout(() => {
                        card.classList.add('visible');
                    }, index * 100);
                });
            } else {
                entry.target.classList.remove('visible');
                const cards = entry.target.querySelectorAll('.service-card, .feature-card, .team-card, .case-card');
                cards.forEach(card => {
                    card.classList.remove('visible');
                });
            }
        });
    }, observerOptions);

    elements.sections.forEach(section => {
        observer.observe(section);
    });

    // Smooth scroll with offset
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                const headerOffset = 80;
                const elementPosition = target.getBoundingClientRect().top;
                const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });

    // Card hover animations
    elements.cards.forEach(card => {
        card.addEventListener('mouseenter', () => {
            card.style.animation = 'cardHover 0.5s var(--animation-timing)';
        });
        
        card.addEventListener('mouseleave', () => {
            card.style.animation = '';
        });
    });

    // Detect internet connection status
    function checkInternetStatus() {
        if (!navigator.onLine) {
            // If offline, redirect to the no-internet page
            // Ensure we don't redirect if we are already on the no-internet page
            if (window.location.pathname.indexOf('no-internet.html') === -1) {
                window.location.href = 'no-internet.html';
            }
        } else {
            // If online and currently on the no-internet page, redirect back to index.php
            if (window.location.pathname.indexOf('no-internet.html') !== -1) {
                window.location.href = 'index.php'; // Or window.history.back()
            }
        }
    }

    // Check on page load
    checkInternetStatus();

    // Listen for online/offline events
    window.addEventListener('online', checkInternetStatus);
    window.addEventListener('offline', checkInternetStatus);
});

// Parallax Effect
window.addEventListener('scroll', () => {
    const scrolled = window.pageYOffset;
    
    const heroSection = document.querySelector('.hero-section');
    if (heroSection) {
        heroSection.style.backgroundPositionY = scrolled * 0.5 + 'px';
    }
    
    const scrollElements = document.querySelectorAll('.scroll-reveal');
    scrollElements.forEach(element => {
        const elementTop = element.getBoundingClientRect().top;
        const elementBottom = element.getBoundingClientRect().bottom;
        
        if (elementTop < window.innerHeight && elementBottom > 0) {
            element.classList.add('visible');
        }
    });
}); 
<?php
$page_title = "Contact Us - Lawyex";
$current_page = "contact";

// Include database connection
require_once 'config/database.php';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $subject = $_POST['subject'] ?? '';
    $message = $_POST['message'] ?? '';
    
    // Basic validation
    $errors = [];
    if (empty($name)) $errors[] = "Name is required";
    if (empty($email)) $errors[] = "Email is required";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email format";
    if (empty($subject)) $errors[] = "Subject is required";
    if (empty($message)) $errors[] = "Message is required";
    
    // If no errors, process the form
    if (empty($errors)) {
        // Here you would typically send an email or save to database
        $success = true;
    }
}
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

    <!-- Contact Section -->
    <section class="contact-section py-5">
        <div class="container">
            <div class="row g-4">
                <!-- Contact Information -->
                <div class="col-lg-4" data-aos="fade-right" data-aos-delay="100">
                    <div class="contact-info">
                        <h2 class="section-title">Get in Touch</h2>
                        <p class="mb-4">We're here to help and answer any questions you might have. We look forward to hearing from you.</p>
                        
                        <?php
                        $contact_info = [
                            [
                                'icon' => 'fa-map-marker-alt',
                                'title' => 'Main Branch',
                                'content' => '6th Floor Paradigm Plaza, AB Shetty Circle, Mangalore , D.K'
                            ],
                            [
                                'icon' => 'fa-map-marker-alt',
                                'title' => 'Branch',
                                'content' => '3rd Floor Canara Tower,  Mission Hospital Road, Udupi'
                            ],
                            [
                                'icon' => 'fa-phone',
                                'title' => 'Phone Number',
                                'content' => '+1 234 567 890'
                            ],
                            [
                                'icon' => 'fa-envelope',
                                'title' => 'Email Address',
                                'content' => 'info@lawyex.com'
                            ],
                            [
                                'icon' => 'fa-clock',
                                'title' => 'Working Hours',
                                'content' => 'Monday - Friday: 9:00 AM - 6:00 PM<br>Saturday: 10:00 AM - 2:00 PM'
                            ]
                        ];

                        foreach ($contact_info as $index => $info) {
                            echo '<div class="info-item mb-4" data-aos="fade-up" data-aos-delay="' . (200 + $index * 100) . '">
                                <i class="fas ' . $info['icon'] . ' text-warning me-3"></i>
                                <div>
                                    <h4>' . $info['title'] . '</h4>
                                    <p>' . $info['content'] . '</p>
                                </div>
                            </div>';
                        }
                        ?>
                    </div>
                </div>
                
                <!-- Contact Form -->
                <div class="col-lg-8" data-aos="fade-left" data-aos-delay="100">
                    <div class="contact-form">
                        <h2 class="section-title">Send us a Message</h2>
                        <?php if (!empty($errors)): ?>
                            <div class="alert alert-danger" data-aos="fade-up">
                                <ul class="mb-0">
                                    <?php foreach ($errors as $error): ?>
                                        <li><?php echo htmlspecialchars($error); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (isset($success)): ?>
                            <div class="alert alert-success" data-aos="fade-up">
                                Thank you for your message! We will get back to you soon.
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="needs-validation" novalidate>
                            <div class="row g-3">
                                <div class="col-md-6" data-aos="fade-up" data-aos-delay="200">
                                    <div class="form-group">
                                        <label for="name" class="form-label">Your Name</label>
                                        <input type="text" class="form-control" id="name" name="name" value="<?php echo isset($name) ? htmlspecialchars($name) : ''; ?>" required>
                                        <div class="invalid-feedback">
                                            Please enter your name.
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6" data-aos="fade-up" data-aos-delay="300">
                                    <div class="form-group">
                                        <label for="email" class="form-label">Email Address</label>
                                        <input type="email" class="form-control" id="email" name="email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" required>
                                        <div class="invalid-feedback">
                                            Please enter a valid email address.
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6" data-aos="fade-up" data-aos-delay="400">
                                    <div class="form-group">
                                        <label for="phone" class="form-label">Phone Number</label>
                                        <input type="tel" class="form-control" id="phone" name="phone" value="<?php echo isset($phone) ? htmlspecialchars($phone) : ''; ?>">
                                    </div>
                                </div>
                                <div class="col-md-6" data-aos="fade-up" data-aos-delay="500">
                                    <div class="form-group">
                                        <label for="subject" class="form-label">Subject</label>
                                        <input type="text" class="form-control" id="subject" name="subject" value="<?php echo isset($subject) ? htmlspecialchars($subject) : ''; ?>" required>
                                        <div class="invalid-feedback">
                                            Please enter a subject.
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12" data-aos="fade-up" data-aos-delay="600">
                                    <div class="form-group">
                                        <label for="message" class="form-label">Your Message</label>
                                        <textarea class="form-control" id="message" name="message" rows="5" required><?php echo isset($message) ? htmlspecialchars($message) : ''; ?></textarea>
                                        <div class="invalid-feedback">
                                            Please enter your message.
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12" data-aos="fade-up" data-aos-delay="700">
                                           <button type="submit" class="btn btn-warning btn-lg">Send Message</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Faqs Section -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center mb-5">
                    <h1 class="display-6 mb-3">Frequently Asked Questions</h1>
                    <p class="text-muted">Find answers to common questions about our legal services</p>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="accordion custom-accordion" id="accordionFaq">
                        <?php
                        // Fetch active FAQs ordered by order_index
                        $faq_query = "SELECT * FROM faq WHERE is_active = 1 ORDER BY order_index ASC";
                        $faq_result = $conn->query($faq_query);
                        
                        if ($faq_result && $faq_result->num_rows > 0) {
                            $faq_count = 0;
                            while ($faq = $faq_result->fetch_assoc()) {
                                $faq_count++;
                                $expanded = $faq_count === 1 ? 'true' : 'false';
                                $show = $faq_count === 1 ? 'show' : '';
                        ?>
                        <div class="accordion-item border-0 mb-3">
                            <h2 class="accordion-header" id="heading<?php echo $faq['id']; ?>">
                                <button class="accordion-button <?php echo $faq_count === 1 ? '' : 'collapsed'; ?> shadow-sm" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $faq['id']; ?>" aria-expanded="<?php echo $expanded; ?>" aria-controls="collapse<?php echo $faq['id']; ?>">
                                    <i class="fas fa-question-circle text-warning me-3"></i>
                                    <?php echo htmlspecialchars($faq['question']); ?>
                                </button>
                            </h2>
                            <div id="collapse<?php echo $faq['id']; ?>" class="accordion-collapse collapse <?php echo $show; ?>" aria-labelledby="heading<?php echo $faq['id']; ?>" data-bs-parent="#accordionFaq">
                                <div class="accordion-body bg-light">
                                    <i class="fas fa-info-circle text-warning me-2"></i>
                                    <?php echo nl2br(htmlspecialchars($faq['answer'])); ?>
                                </div>
                            </div>
                        </div>
                        <?php
                            }
                        } else {
                            echo '<div class="alert alert-info">No FAQs available at the moment.</div>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .custom-accordion .accordion-button {
            background-color: #fff;
            color: #333;
            font-weight: 500;
            padding: 1.25rem;
            border-radius: 8px !important;
            transition: all 0.3s ease;
        }
        
        .custom-accordion .accordion-button:not(.collapsed) {
            background-color: #fff;
            color: #bc841c;
            box-shadow: 0 0 15px rgba(188, 132, 28, 0.1);
        }
        
        .custom-accordion .accordion-button:focus {
            box-shadow: none;
            border-color: rgba(188, 132, 28, 0.2);
        }
        
        .custom-accordion .accordion-body {
            padding: 1.5rem;
            border-radius: 0 0 8px 8px;
            font-size: 0.95rem;
            line-height: 1.7;
        }
        
        .custom-accordion .accordion-button::after {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23bc841c'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
            transition: all 0.3s ease;
        }
    </style>
    <!-- Faqs End -->

    <!-- Map Section -->
    <section class="map-section" data-aos="fade-up" data-aos-delay="100">
        <div class="container-fluid p-0">
            <div class="row g-0">
                <div class="col-12">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3889.735244244663!2d74.83451207619336!3d12.860369284960484!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ba35b6aa9117fad%3A0x5599e36896382df0!2sLex%20juris%20law%20chamber%20Mangalore!5e0!3m2!1sen!2sin!4v1750313340054!5m2!1sen!2sin" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
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

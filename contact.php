<?php
$page_title = "Contact Us - LexJuris";
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
    <section class="contact-section py-5" style="background: var(--light-bg);">
        <div class="container">
            <div class="row justify-content-center align-items-start g-0">
                <!-- Contact Form -->
                <div class="col-lg-7 px-4 py-5">
                    <h1 class="display-4 mb-2" style="font-family: 'Playfair Display', serif; font-weight: 700; color: var(--primary-color);">Contact Law Firm</h1>
                    <p class="mb-4" style="color: #666;">Send a message. We will contact you as soon as possible</p>
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
                            <div class="col-12">
                                <input type="text" class="form-control bg-white border-0 rounded-3 py-3" placeholder="Your Name (*)" id="name" name="name" value="<?php echo isset($name) ? htmlspecialchars($name) : ''; ?>" required>
                                <div class="invalid-feedback">Please enter your name.</div>
                            </div>
                            <div class="col-12">
                                <input type="email" class="form-control bg-white border-0 rounded-3 py-3" placeholder="Your Email (*)" id="email" name="email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" required>
                                <div class="invalid-feedback">Please enter a valid email address.</div>
                            </div>
                            <div class="col-12">
                                <input type="text" class="form-control bg-white border-0 rounded-3 py-3" placeholder="Subject" id="subject" name="subject" value="<?php echo isset($subject) ? htmlspecialchars($subject) : ''; ?>" required>
                                <div class="invalid-feedback">Please enter a subject.</div>
                            </div>
                            <div class="col-12">
                                <textarea class="form-control bg-white border-0 rounded-3 py-3" placeholder="Your Message" id="message" name="message" rows="5" required><?php echo isset($message) ? htmlspecialchars($message) : ''; ?></textarea>
                                <div class="invalid-feedback">Please enter your message.</div>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-warning px-5 py-2 rounded-3" style="background: var(--secondary-color); border: none; color: #fff;">Send Message</button>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- Contact Information -->
                <div class="col-lg-5 d-flex align-items-stretch">
                    <div class="bg-white rounded-4 shadow-sm p-4 w-100" style="min-width: 320px; border: 2px solid var(--primary-color);">
                        <div class="mb-4 d-flex align-items-center">
                            <span class="d-inline-flex justify-content-center align-items-center" style="background: var(--primary-color); width:48px;height:48px; border-radius: 12px;" ><i class="fas fa-phone fa-lg text-white"></i></span>
                            <div class="ms-3">
                                <div class="fw-bold" style="color: var(--secondary-color);">Call Free</div>
                                <div class="text-muted small">+91 7411448378, +91 9555552545</div>
                            </div>
                        </div>
                        <div class="mb-4 d-flex align-items-center">
                            <span class="d-inline-flex justify-content-center align-items-center" style="background: var(--primary-color); width:48px;height:48px; border-radius: 12px;"><i class="fas fa-envelope fa-lg text-white"></i></span>
                            <div class="ms-3">
                                <div class="fw-bold" style="color: var(--secondary-color);">Email</div>
                                <div class="text-muted small">teamlexjuris@gmail.com</div>
                            </div>
                        </div>
                        <div class="mb-4 d-flex align-items-center">
                            <span class="d-inline-flex justify-content-center align-items-center" style="background: var(--primary-color); width:48px;height:48px; border-radius: 12px;"><i class="fas fa-map-marker-alt fa-lg text-white"></i></span>
                            <div class="ms-3">
                                <div class="fw-bold" style="color: var(--secondary-color);">Head Office</div>
                                <div class="text-muted small">6th Floor Paradigm Plaza, AB Shetty Circle, Mangalore , D.K</div>
                            </div>
                        </div>
                        <div class="mb-4 d-flex align-items-center">
                            <span class="d-inline-flex justify-content-center align-items-center" style="background: var(--primary-color); width:48px;height:48px; border-radius: 12px;"><i class="fas fa-map-marker-alt fa-lg text-white"></i></span>
                            <div class="ms-3">
                                <div class="fw-bold" style="color: var(--secondary-color);">Branch</div>
                                <div class="text-muted small">3rd Floor Canara Tower,  Mission Hospital Road, Udupi</div>
                            </div>
                        </div>
                        <div class="mb-2 d-flex align-items-center">
                            <span class="d-inline-flex justify-content-center align-items-center" style="background: var(--primary-color); width:48px;height:48px; border-radius: 12px;"><i class="fas fa-clock fa-lg text-white"></i></span>
                            <div class="ms-3">
                                <div class="fw-bold" style="color: var(--secondary-color);">Working Hours</div>
                                <div class="text-muted small">Monday - Saturday: 9:00 AM - 9:00 PM<br>Saturday: 9:00 AM - 2:00 PM</div>
                            </div>
                        </div>
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

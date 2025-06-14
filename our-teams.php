<?php
$page_title = "Our Teams - Lawyex";
$current_page = "our-teams";

// Include database connection
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

    <!-- Main Team Section -->
    <section class="team-section py-5">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-12" data-aos="fade-up">
                    <h2 class="section-title">Our Main Team</h2>
                    <p class="section-subtitle">Meet our experienced legal professionals</p>
                </div>
            </div>
            <div class="row g-4 justify-content-center">
                <?php
                // Fetch main team members (assuming they have order_index < 10)
                $main_team_query = "SELECT * FROM team_members WHERE is_active = 1 AND order_index < 10 ORDER BY order_index ASC";
                $main_team_result = $conn->query($main_team_query);
                
                if ($main_team_result && $main_team_result->num_rows > 0) {
                    $index = 0;
                    while ($member = $main_team_result->fetch_assoc()) {
                        // Fetch social links for this team member
                        $social_query = "SELECT * FROM team_social_links WHERE team_id = ? AND is_active = 1 ORDER BY FIELD(platform, 'LinkedIn', 'Twitter', 'Email', 'Facebook', 'Instagram', 'GitHub', 'Other')";
                        $social_stmt = $conn->prepare($social_query);
                        $social_stmt->bind_param("i", $member['id']);
                        $social_stmt->execute();
                        $social_result = $social_stmt->get_result();
                        
                        echo '<div class="col-md-4 mb-4" data-aos="zoom-in" data-aos-delay="' . ($index * 100) . '">
                            <div class="main-team-card">
                                <img src="' . htmlspecialchars($member['photo']) . '" alt="' . htmlspecialchars($member['full_name']) . '" class="team-img">
                                <div class="team-info-overlay">
                                    <h3>
                                        <a href="' . (!empty($member['portfolio']) ? htmlspecialchars($member['portfolio']) : '#') . '" target="_blank" class="text-white text-decoration-none">
                                            ' . htmlspecialchars($member['full_name']) . '
                                        </a>
                                    </h3>
                                    <p>' . htmlspecialchars($member['position']) . '</p>';
                        
                        // Add social links if they exist
                        if ($social_result && $social_result->num_rows > 0) {
                            echo '<div class="team-social-links">';
                            while ($social = $social_result->fetch_assoc()) {
                                $icon_class = '';
                                $platform = $social['platform'];
                                
                                switch ($platform) {
                                    case 'LinkedIn':
                                        $icon_class = 'fab fa-linkedin';
                                        break;
                                    case 'Twitter':
                                        $icon_class = 'fab fa-twitter';
                                        break;
                                    case 'Email':
                                        $icon_class = 'fas fa-envelope';
                                        break;
                                    case 'Facebook':
                                        $icon_class = 'fab fa-facebook';
                                        break;
                                    case 'Instagram':
                                        $icon_class = 'fab fa-instagram';
                                        break;
                                    case 'GitHub':
                                        $icon_class = 'fab fa-github';
                                        break;
                                    case 'Other':
                                        $icon_class = 'fas fa-link';
                                        break;
                                }
                                
                                // Format URL based on platform
                                $url = $social['url'];
                                if ($platform === 'Email' && !str_starts_with($url, 'mailto:')) {
                                    $url = 'mailto:' . $url;
                                }
                                
                                echo '<a href="' . htmlspecialchars($url) . '" target="_blank" class="social-link" title="' . htmlspecialchars($platform) . '">
                                    <i class="' . $icon_class . '"></i>
                                </a>';
                            }
                            echo '</div>';
                        }
                        
                        echo '</div></div></div>';
                        $index++;
                    }
                } else {
                    // Fallback if no team members found
                    echo '<div class="col-12 text-center">
                        <p class="text-muted">No team members found.</p>
                    </div>';
                }
                ?>
            </div>
        </div>
    </section>

    <!-- Sub Junior Team Section -->
    <section class="team-section py-5 bg-light">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-12" data-aos="fade-up">
                    <h2 class="section-title">Sub Junior Team</h2>
                    <p class="section-subtitle">Meet our talented sub junior team members</p>
                </div>
            </div>
            <div class="row g-4 justify-content-center">
                <?php
                // Fetch sub junior team members (assuming they have order_index >= 10)
                $sub_junior_query = "SELECT * FROM sub_junior_team_members WHERE is_active = 1 ORDER BY order_index ASC";
                $sub_junior_result = $conn->query($sub_junior_query);
                
                if ($sub_junior_result && $sub_junior_result->num_rows > 0) {
                    $index = 0;
                    while ($member = $sub_junior_result->fetch_assoc()) {
                        // Fetch social links for this team member
                        $social_query = "SELECT * FROM sub_junior_social_links WHERE sub_junior_id = ? AND is_active = 1 ORDER BY FIELD(platform, 'LinkedIn', 'Twitter', 'Email', 'Facebook', 'Instagram', 'GitHub', 'Other')";
                        $social_stmt = $conn->prepare($social_query);
                        $social_stmt->bind_param("i", $member['id']);
                        $social_stmt->execute();
                        $social_result = $social_stmt->get_result();
                        
                        echo '<div class="col-md-4 mb-4" data-aos="zoom-in" data-aos-delay="' . ($index * 100) . '">
                            <div class="main-team-card">
                                <img src="' . htmlspecialchars($member['photo']) . '" alt="' . htmlspecialchars($member['full_name']) . '" class="team-img">
                                <div class="team-info-overlay">
                                    <h3>
                                        <a href="' . (!empty($member['portfolio']) ? htmlspecialchars($member['portfolio']) : '#') . '" target="_blank" class="text-white text-decoration-none">
                                            ' . htmlspecialchars($member['full_name']) . '
                                        </a>
                                    </h3>
                                    <p>' . htmlspecialchars($member['position']) . '</p>';
                        
                        // Add social links if they exist
                        if ($social_result && $social_result->num_rows > 0) {
                            echo '<div class="team-social-links">';
                            while ($social = $social_result->fetch_assoc()) {
                                $icon_class = '';
                                $platform = $social['platform'];
                                
                                switch ($platform) {
                                    case 'LinkedIn':
                                        $icon_class = 'fab fa-linkedin';
                                        break;
                                    case 'Twitter':
                                        $icon_class = 'fab fa-twitter';
                                        break;
                                    case 'Email':
                                        $icon_class = 'fas fa-envelope';
                                        break;
                                    case 'Facebook':
                                        $icon_class = 'fab fa-facebook';
                                        break;
                                    case 'Instagram':
                                        $icon_class = 'fab fa-instagram';
                                        break;
                                    case 'GitHub':
                                        $icon_class = 'fab fa-github';
                                        break;
                                    case 'Other':
                                        $icon_class = 'fas fa-link';
                                        break;
                                    case 'WhatsApp':
                                        $icon_class = 'fab fa-whatsapp';
                                        break;
                                    case 'YouTube':
                                        $icon_class = 'fab fa-youtube';
                                        break;
                                    case 'Website':
                                        $icon_class = 'fas fa-globe';
                                        break;
                                    case 'Phone':
                                        $icon_class = 'fas fa-phone';
                                        break;
                                }
                                
                                // Format URL based on platform
                                $url = $social['url'];
                                if ($platform === 'Email' && !str_starts_with($url, 'mailto:')) {
                                    $url = 'mailto:' . $url;
                                } else if ($platform === 'Phone' && !str_starts_with($url, 'tel:')) {
                                    $url = 'tel:' . $url;
                                } else if ($platform === 'WhatsApp' && !str_starts_with($url, 'https://wa.me/')) {
                                    // Assuming WhatsApp numbers are stored without the full URL
                                    $url = 'https://wa.me/' . preg_replace('/^\+/', '', $url); // Remove leading + if exists
                                }
                                
                                echo '<a href="' . htmlspecialchars($url) . '" target="_blank" class="social-link" title="' . htmlspecialchars($platform) . '">
                                    <i class="' . $icon_class . '"></i>
                                </a>';
                            }
                            echo '</div>';
                        }
                        
                        echo '</div></div></div>';
                        $index++;
                    }
                } else {
                    // Fallback if no team members found
                    echo '<div class="col-12 text-center">
                        <p class="text-muted">No sub junior team members found.</p>
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

    <style>
        .main-team-card, .team-card {
            position: relative;
            overflow: hidden;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
            margin-bottom: 20px;
        }
        
        .main-team-card:hover, .team-card:hover {
            transform: translateY(-5px);
        }
        
        .team-img, .team-card img {
            width: 100%;
            height: 350px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        
        .main-team-card:hover .team-img, .team-card:hover img {
            transform: scale(1.05);
        }
        
        .team-info-overlay, .team-info {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.9), rgba(0,0,0,0.4));
            padding: 20px;
            color: white;
            transition: all 0.3s ease;
        }
        
        .main-team-card:hover .team-info-overlay,
        .team-card:hover .team-info {
            background: linear-gradient(to top, rgba(0,0,0,0.95), rgba(0,0,0,0.6));
        }
        
        .team-info-overlay h3, .team-info h3 {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 600;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
        }
        
        .team-info-overlay p, .team-info p {
            margin: 5px 0 0;
            font-size: 0.9rem;
            opacity: 0.9;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
        }
        
        .team-social-links {
            margin-top: 12px;
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            justify-content: center;
        }
        
        .social-link {
            color: white;
            font-size: 1rem;
            transition: all 0.3s ease;
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(5px);
            text-decoration: none;
        }
        
        .social-link:hover {
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        
        /* Platform-specific colors on hover */
        .social-link[title="LinkedIn"]:hover { background: #0077b5; }
        .social-link[title="Twitter"]:hover { background: #1da1f2; }
        .social-link[title="Facebook"]:hover { background: #4267B2; }
        .social-link[title="Instagram"]:hover { background: #E1306C; }
        .social-link[title="GitHub"]:hover { background: #333; }
        .social-link[title="Email"]:hover { background: #EA4335; }
        .social-link[title="Other"]:hover { background: #bc841c; }
    </style>
</body>
</html> 
<?php
$page_title = "Our Teams - Lawyex";
$current_page = "our-teams";
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
                $main_team = [
                    [
                        'image' => 'https://images.unsplash.com/photo-1521737852567-6949f3f9f2b5?auto=format&fit=crop&w=400&q=80',
                        'name' => 'Omer Farooq Mulki',
                        'position' => 'B.A. (Law), LL.B',
                        'portfolio' => 'portfolio1.html'
                    ],
                    [
                        'image' => 'https://images.unsplash.com/photo-1515378791036-0648a3ef77b2?auto=format&fit=crop&w=400&q=80',
                        'name' => 'Asif Baikady',
                        'position' => 'B.Com, LL.B',
                        'portfolio' => 'portfolio2.html'
                    ],
                    [
                        'image' => 'https://images.unsplash.com/photo-1503676382389-4809596d5290?auto=format&fit=crop&w=400&q=80',
                        'name' => 'Mahammad Asgar',
                        'position' => 'B.A. (Law), LL.B',
                        'portfolio' => 'portfolio3.html'
                    ],
                    [
                        'image' => 'https://images.unsplash.com/photo-1464983953574-0892a716854b?auto=format&fit=crop&w=400&q=80',
                        'name' => 'Abu Harish',
                        'position' => 'B.A. (Law), LL.B',
                        'portfolio' => 'portfolio4.html'
                    ],
                    [
                        'image' => 'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?auto=format&fit=crop&w=400&q=80',
                        'name' => 'Umarul Farook',
                        'position' => 'B.A., LL.B',
                        'portfolio' => 'portfolio5.html'
                    ],
                    [
                        'image' => 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=400&q=80',
                        'name' => 'I.M. Ijaz Ahmed Ullal',
                        'position' => 'B.A. (Law), LL.B',
                        'portfolio' => 'portfolio6.html'
                    ]
                ];
                foreach ($main_team as $index => $member) {
                    echo '<div class="col-md-4 mb-4" data-aos="zoom-in" data-aos-delay="' . ($index * 100) . '">
                        <div class="main-team-card">
                            <img src="' . $member['image'] . '" alt="' . $member['name'] . '">
                            <div class="team-info-overlay">
                                <h3>' . $member['name'] . '</h3>
                                <p>' . $member['position'] . '</p>
                            </div>
                        </div>
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
                $sub_junior_team = [
                    [
                        'image' => 'https://images.unsplash.com/photo-1521737852567-6949f3f9f2b5?auto=format&fit=crop&w=400&q=80',
                        'name' => 'Abubakkar Sidiq M',
                        'position' => 'B.A. (Law), LL.B'
                    ],
                    [
                        'image' => 'https://images.unsplash.com/photo-1515378791036-0648a3ef77b2?auto=format&fit=crop&w=400&q=80',
                        'name' => 'Ritesh Bangera',
                        'position' => 'B.A., LL.B'
                    ],
                    [
                        'image' => 'https://images.unsplash.com/photo-1503676382389-4809596d5290?auto=format&fit=crop&w=400&q=80',
                        'name' => 'Mohammed A. R.',
                        'position' => 'B.Com, LL.B'
                    ],
                    [
                        'image' => 'https://images.unsplash.com/photo-1464983953574-0892a716854b?auto=format&fit=crop&w=400&q=80',
                        'name' => 'K. Mufeedha Rahman',
                        'position' => 'B.A. (Law), LL.B'
                    ],
                    [
                        'image' => 'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?auto=format&fit=crop&w=400&q=80',
                        'name' => 'Alisha Zulka',
                        'position' => 'B.A. (Law), LL.B'
                    ],
                    [
                        'image' => 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=400&q=80',
                        'name' => 'Haidarali M. H.',
                        'position' => 'B.A., LL.B'
                    ],
                    [
                        'image' => 'https://images.unsplash.com/photo-1521737852567-6949f3f9f2b5?auto=format&fit=crop&w=400&q=80',
                        'name' => 'Niriksha',
                        'position' => 'B.A. (Law), LL.B'
                    ],
                    [
                        'image' => 'https://images.unsplash.com/photo-1515378791036-0648a3ef77b2?auto=format&fit=crop&w=400&q=80',
                        'name' => 'Ashika Hussain',
                        'position' => 'B.A. (Law), LL.B'
                    ],
                    [
                        'image' => 'https://images.unsplash.com/photo-1503676382389-4809596d5290?auto=format&fit=crop&w=400&q=80',
                        'name' => 'Lloyd D Mello',
                        'position' => 'B.Com, LL.B'
                    ],
                     [
                        'image' => 'https://images.unsplash.com/photo-1464983953574-0892a716854b?auto=format&fit=crop&w=400&q=80',
                        'name' => 'Irshad Himami Saqafi Montepadav',
                        'position' => 'B.A., LL.B'
                    ],
                     [
                        'image' => 'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?auto=format&fit=crop&w=400&q=80',
                        'name' => 'Mohammed Adil',
                        'position' => 'B.A., LL.B'
                    ],
                     [
                        'image' => 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=400&q=80',
                        'name' => 'Mansoor Ali',
                        'position' => 'B.A., LL.B'
                    ],
                     [
                        'image' => 'https://images.unsplash.com/photo-1521737852567-6949f3f9f2b5?auto=format&fit=crop&w=400&q=80',
                        'name' => 'Rubeena K. P',
                        'position' => 'B.A., LL.B'
                    ],
                     [
                        'image' => 'https://images.unsplash.com/photo-1515378791036-0648a3ef77b2?auto=format&fit=crop&w=400&q=80',
                        'name' => 'Mahammad Sadiq',
                        'position' => 'B.A., LL.B'
                    ],
                     [
                        'image' => 'https://images.unsplash.com/photo-1503676382389-4809596d5290?auto=format&fit=crop&w=400&q=80',
                        'name' => 'Sanjana Latha',
                        'position' => 'B.A., LL.B'
                    ],
                    [
                        'image' => 'https://images.unsplash.com/photo-1464983953574-0892a716854b?auto=format&fit=crop&w=400&q=80',
                        'name' => 'Ayaz Charmady',
                        'position' => 'B.Sc., LL.B'
                    ],
                     [
                        'image' => 'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?auto=format&fit=crop&w=400&q=80',
                        'name' => 'Shounak Rai',
                        'position' => 'B.A. (Law), LL.B'
                    ],
                    [
                        'image' => 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=400&q=80',
                        'name' => 'Afeeza',
                        'position' => 'B.A.(LAW), LL.B'
                    ],
                     [
                        'image' => 'https://images.unsplash.com/photo-1521737852567-6949f3f9f2b5?auto=format&fit=crop&w=400&q=80',
                        'name' => 'Mahammad Nishan M K',
                        'position' => 'BBA.,LL.B'
                    ]
                ];
                foreach ($sub_junior_team as $index => $member) {
                    echo '<div class="col-md-4 mb-4" data-aos="zoom-in" data-aos-delay="' . ($index * 100) . '">
                        <div class="team-card">
                            <img src="' . $member['image'] . '" alt="' . $member['name'] . '" class="img-fluid">
                            <div class="team-info">
                                <h3>' . $member['name'] . '</h3>
                                <p>' . $member['position'] . '</p>
                            </div>
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
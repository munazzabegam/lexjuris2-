<?php
$page_title = "Our Blog - Lawyex";
$current_page = "blog";

// Sample blog posts data
$blog_posts = [
    [
        'image' => 'assets/images/blog-1.jpg',
        'date' => 'March 15, 2024',
        'author' => 'John Doe',
        'category' => 'Legal News',
        'title' => 'Understanding Your Rights in a Divorce Case',
        'excerpt' => 'Learn about the key aspects of divorce proceedings and how to protect your rights during this challenging time.',
        'link' => '#'
    ],
    [
        'image' => 'assets/images/blog-2.jpg',
        'date' => 'March 10, 2024',
        'author' => 'Jane Smith',
        'category' => 'Immigration Law',
        'title' => 'Recent Changes in Immigration Policies',
        'excerpt' => 'Stay informed about the latest updates in immigration laws and how they might affect your case.',
        'link' => '#'
    ],
    [
        'image' => 'assets/images/blog-3.jpg',
        'date' => 'March 5, 2024',
        'author' => 'Mike Johnson',
        'category' => 'Business Law',
        'title' => 'Protecting Your Business: Legal Tips',
        'excerpt' => 'Essential legal considerations for business owners to ensure compliance and protect their interests.',
        'link' => '#'
    ]
];

// Sample categories
$categories = [
    ['name' => 'Legal News', 'count' => 12],
    ['name' => 'Immigration Law', 'count' => 8],
    ['name' => 'Business Law', 'count' => 15],
    ['name' => 'Family Law', 'count' => 10],
    ['name' => 'Criminal Law', 'count' => 7]
];

// Sample recent posts
$recent_posts = [
    ['title' => 'Understanding Your Rights in a Divorce Case', 'date' => 'March 15, 2024'],
    ['title' => 'Recent Changes in Immigration Policies', 'date' => 'March 10, 2024'],
    ['title' => 'Protecting Your Business: Legal Tips', 'date' => 'March 5, 2024']
];

// Sample tags
$tags = ['Legal', 'Immigration', 'Business', 'Family', 'Criminal', 'Divorce', 'Rights', 'Policy'];
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

    <!-- Blog Section -->
    <section class="blog-section py-5">
        <div class="container">
            <div class="row g-4">
                <!-- Blog Posts -->
                <div class="col-lg-8">
                    <?php foreach ($blog_posts as $post): ?>
                    <div class="blog-card mb-4">
                        <div class="row g-0">
                            <div class="col-md-4">
                                <img src="<?php echo $post['image']; ?>" class="img-fluid rounded-start" alt="<?php echo $post['title']; ?>">
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <div class="blog-meta mb-2">
                                        <span><i class="far fa-calendar-alt me-2"></i><?php echo $post['date']; ?></span>
                                        <span class="ms-3"><i class="far fa-user me-2"></i><?php echo $post['author']; ?></span>
                                        <span class="ms-3"><i class="far fa-folder me-2"></i><?php echo $post['category']; ?></span>
                                    </div>
                                    <h3 class="card-title"><?php echo $post['title']; ?></h3>
                                    <p class="card-text"><?php echo $post['excerpt']; ?></p>
                                    <a href="<?php echo $post['link']; ?>" class="btn btn-warning">Read More</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>

                    <!-- Pagination -->
                    <nav aria-label="Blog pagination">
                        <ul class="pagination justify-content-center">
                            <li class="page-item disabled">
                                <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                            </li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item">
                                <a class="page-link" href="#">Next</a>
                            </li>
                        </ul>
                    </nav>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Search Widget -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <h4 class="card-title">Search</h4>
                            <form class="d-flex">
                                <input class="form-control me-2" type="search" placeholder="Search..." aria-label="Search">
                                <button class="btn btn-warning" type="submit"><i class="fas fa-search"></i></button>
                            </form>
                        </div>
                    </div>

                    <!-- Categories Widget -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <h4 class="card-title">Categories</h4>
                            <ul class="list-unstyled">
                                <?php foreach ($categories as $category): ?>
                                <li class="mb-2">
                                    <a href="#" class="text-decoration-none">
                                        <?php echo $category['name']; ?>
                                        <span class="badge bg-warning float-end"><?php echo $category['count']; ?></span>
                                    </a>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>

                    <!-- Recent Posts Widget -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <h4 class="card-title">Recent Posts</h4>
                            <ul class="list-unstyled">
                                <?php foreach ($recent_posts as $post): ?>
                                <li class="mb-3">
                                    <a href="#" class="text-decoration-none">
                                        <h6 class="mb-1"><?php echo $post['title']; ?></h6>
                                        <small class="text-muted"><?php echo $post['date']; ?></small>
                                    </a>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>

                    <!-- Tags Widget -->
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Tags</h4>
                            <div class="tags">
                                <?php foreach ($tags as $tag): ?>
                                <a href="#" class="btn btn-sm btn-outline-warning me-2 mb-2"><?php echo $tag; ?></a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
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
<?php
require_once 'includes/loader.php';

$nav_items = [
    'home' => ['url' => 'index.php', 'text' => 'Home'],
    'about' => ['url' => 'about.php', 'text' => 'About'],
    'services' => ['url' => 'services.php', 'text' => 'Services'],
    'cases' => ['url' => 'cases.php', 'text' => 'Cases'],
    'blog' => ['url' => 'blog.php', 'text' => 'Blog'],
    'contact' => ['url' => 'contact.php', 'text' => 'Contact'],
    'our-teams' => ['url' => 'our-teams.php', 'text' => 'Our Teams']
    
];
?>
<!-- Navigation -->
<nav class="navbar navbar-expand-lg fixed-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="index.php">
            <img src="assets/images/logo.png" alt="Lex Juris Logo" style="height:50px;width:auto;margin-right:10px;">
            <span class="text-warning">Lex</span> Juris
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <?php foreach ($nav_items as $key => $item): ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page === $key) ? 'active' : ''; ?>" 
                           href="<?php echo $item['url']; ?>">
                            <?php echo $item['text']; ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- AOS CSS -->
<link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
<!-- AOS JS -->
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>
  AOS.init({
    duration: 1200,
    once: true
  });
</script>

<?php if ($current_page !== 'home'): ?>
<!-- Page Header -->
<header class="page-header">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <h1 class="display-4 text-white"><?php echo $page_title; ?></h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center">
                        <li class="breadcrumb-item"><a href="index.php" class="text-white">Home</a></li>
                        <li class="breadcrumb-item active text-warning" aria-current="page"><?php echo ucfirst($current_page); ?></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</header>
<?php endif; ?> 
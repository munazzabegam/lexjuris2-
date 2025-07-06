<?php
require_once 'includes/loader.php';

$nav_items = [
    'home' => ['url' => 'index.php', 'text' => 'Home'],
    'about' => ['url' => 'about.php', 'text' => 'About'],
    'services' => ['url' => 'services.php', 'text' => 'Services'],
    'blog' => ['url' => 'blog.php', 'text' => 'Blog'],
    'contact' => ['url' => 'contact.php', 'text' => 'Contact'],
    'our-teams' => ['url' => 'our-teams.php', 'text' => 'Our Teams']
    
];
?>
<!-- Navigation -->
<nav class="navbar navbar-expand-lg fixed-top navbar-blur">
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

<style>
  .navbar-blur {
    background: rgba(255,255,255,0.7) !important;
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    transition: background 0.3s, backdrop-filter 0.3s;
  }
</style>

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
                        <li class="breadcrumb-item active" aria-current="page" style="color: #bc841c;"><?php echo ucfirst($current_page); ?></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</header>
<?php endif; ?> 
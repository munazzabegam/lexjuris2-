<?php
require_once(__DIR__ . '/loader.php');

// Get the project folder dynamically
$project_folder = explode('/', $_SERVER['SCRIPT_NAME'])[1];
$project_base = '/' . $project_folder . '/';

$current_full_path = $_SERVER['PHP_SELF'];

$nav_items = [
    'home' => ['url' => 'index.php', 'text' => 'Home', 'match' => '/index.php'],
    'about' => ['url' => 'about/', 'text' => 'About', 'match' => '/about/'],
    'services' => ['url' => 'services/', 'text' => 'Services', 'match' => '/services/'],
    'our-teams' => ['url' => 'teams/', 'text' => 'Teams', 'match' => '/teams/'],
    'blog' => ['url' => 'blog/', 'text' => 'Blog', 'match' => '/blog/'],
    'contact' => ['url' => 'contact/', 'text' => 'Contact', 'match' => '/contact/']
];
?>
<!-- Navigation -->
<nav class="navbar navbar-expand-lg fixed-top navbar-blur">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="<?php echo ($current_page === 'home') ? 'index.php' : '../index.php'; ?>">
            <img src="<?php echo ($current_page === 'home') ? 'assets/images/logo.png' : '../assets/images/logo.png'; ?>" alt="Lex Juris Logo" style="height:50px;width:auto;margin-right:10px;">
            <span class="text-warning">Lex</span> Juris
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_full_path === $project_base . 'index.php') ? 'active' : ''; ?>" href="<?php echo $project_base; ?>index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo strpos($current_full_path, '/about/') !== false ? 'active' : ''; ?>" href="<?php echo $project_base; ?>about/">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo strpos($current_full_path, '/services/') !== false ? 'active' : ''; ?>" href="<?php echo $project_base; ?>services/">Services</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo strpos($current_full_path, '/teams/') !== false ? 'active' : ''; ?>" href="<?php echo $project_base; ?>teams/">Teams</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo strpos($current_full_path, '/blog/') !== false ? 'active' : ''; ?>" href="<?php echo $project_base; ?>blog/">Blog</a>
                </li>
                    <li class="nav-item">
                    <a class="nav-link <?php echo strpos($current_full_path, '/contact/') !== false ? 'active' : ''; ?>" href="<?php echo $project_base; ?>contact/">Contact</a>
                    </li>
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
                        <li class="breadcrumb-item"><a href="<?php echo ($current_page === 'home') ? 'index.php' : '../index.php'; ?>" class="text-white">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page" style="color: #bc841c;"><?php echo ucfirst($current_page); ?></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</header>
<?php endif; ?> 
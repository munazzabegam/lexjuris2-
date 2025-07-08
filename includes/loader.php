<?php
// Loader styles
$loader_styles = "
<style>
    .page-loader {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(30px);
        -webkit-backdrop-filter: blur(30px);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 99999;
        transition: opacity 0.5s ease-out;
        margin: 0;
        padding: 0;
    }
    

    .loader-content {
        text-align: center;
        background: rgba(255, 255, 255, 0.7);
        padding: 40px;
        border-radius: 50%;
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }

    .loader-logo {
        width: 100px;
        height: 100px;
        animation: pulse 1.5s infinite;
    }

    @keyframes pulse {
        0% {
            transform: scale(1);
            opacity: 1;
        }
        50% {
            transform: scale(1.1);
            opacity: 0.8;
        }
        100% {
            transform: scale(1);
            opacity: 1;
        }
    }
</style>
";

// Loader HTML
$loader_html = "
<div class='page-loader'>
    <div class='loader-content'>
        <img src='" . (($current_page === 'home') ? 'assets/images/logo.png' : '../assets/images/logo.png') . "' alt='Lex Juris Logo' class='loader-logo'>
    </div>
</div>
";

// Loader JavaScript
$loader_script = "
<script>
    window.addEventListener('load', function() {
        const loader = document.querySelector('.page-loader');
        loader.style.opacity = '0';
        setTimeout(() => {
            loader.style.display = 'none';
        }, 500);
    });
</script>
";

// Output the loader
echo $loader_styles;
echo $loader_html;
echo $loader_script;
?> 
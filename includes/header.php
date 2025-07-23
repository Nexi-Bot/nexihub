<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - Nexi Hub' : 'Nexi Hub - Your Digital Empire, Supercharged'; ?></title>
    <meta name="description" content="<?php echo isset($page_description) ? $page_description : 'Nexi Hub - The governing company behind Nexi Web, Nexi Bot, and Nexi Pulse. Your digital empire, supercharged.'; ?>">
    <link rel="icon" type="image/x-icon" href="/assets/nexi.ico">
    <link rel="shortcut icon" href="/assets/nexi.ico">
    <link rel="apple-touch-icon" href="/assets/nexi.ico">
    <link rel="stylesheet" href="/assets/style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="/assets/css/consent-popup.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="/assets/js/consent-popup.js?v=<?php echo time(); ?>" defer></script>
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <a href="/" class="logo-link" style="justify-content:center;align-items:center;">
                        <img src="/nexi.png" alt="Nexi Hub" class="logo-image logo-image-large">
                    </a>
                </div>
                
                <nav class="nav">
                    <ul class="nav-list">
                        <?php
                        // Get current route for active navigation
                        $current_route = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
                        if (empty($current_route)) $current_route = 'home';
                        ?>
                        <li><a href="/" class="nav-link <?php echo ($current_route == 'home' || $current_route == '') ? 'active' : ''; ?>">Home</a></li>
                        <li><a href="/about" class="nav-link <?php echo ($current_route == 'about') ? 'active' : ''; ?>">About Nexi</a></li>
                        <li><a href="/team" class="nav-link <?php echo ($current_route == 'team') ? 'active' : ''; ?>">The Team</a></li>
                        <li><a href="/careers" class="nav-link <?php echo ($current_route == 'careers') ? 'active' : ''; ?>">Careers</a></li>
                        <li><a href="/contact" class="nav-link <?php echo ($current_route == 'contact') ? 'active' : ''; ?>">Contact</a></li>
                        <li><a href="/legal" class="nav-link <?php echo ($current_route == 'legal') ? 'active' : ''; ?>">Legal</a></li>
                    </ul>
                </nav>
                
                <div class="header-actions">
                    <a href="/staff/login" class="btn btn-primary">Staff Login</a>
                </div>
            </div>
        </div>
    </header>

    <main class="main">

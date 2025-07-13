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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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
                        <li><a href="/" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : ''; ?>">Home</a></li>
                        <li><a href="/about" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'about.php') ? 'active' : ''; ?>">About Nexi</a></li>
                        <li><a href="/team" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'team.php') ? 'active' : ''; ?>">The Team</a></li>
                        <li><a href="/careers" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'careers.php') ? 'active' : ''; ?>">Careers</a></li>
                        <li><a href="/contact" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'contact.php') ? 'active' : ''; ?>">Contact</a></li>
                        <li><a href="/legal" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'legal.php') ? 'active' : ''; ?>">Legal</a></li>
                    </ul>
                </nav>
                
                <div class="header-actions">
                    <a href="/login" class="btn btn-primary">Staff Login</a>
                </div>
            </div>
        </div>
    </header>

    <main class="main">

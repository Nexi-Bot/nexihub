<?php
// Local development router for clean URLs
// Use this when running: php -S localhost:8000 router.php

$request_uri = $_SERVER['REQUEST_URI'];
$path = parse_url($request_uri, PHP_URL_PATH);

// Remove leading slash and trailing slash
$path = trim($path, '/');

// Route mappings for clean URLs
$routes = [
    // Main pages
    'about' => 'about.php',
    'team' => 'team.php',
    'careers' => 'careers.php',
    'legal' => 'legal.php',
    'contact' => 'contact.php',
    
    // Staff pages
    'staff/login' => 'staff/login.php',
    'staff/discord-auth' => 'staff/discord-auth.php',
    'staff/discord-callback' => 'staff/discord-callback.php',
    'staff/email-auth' => 'staff/email-auth.php',
    'staff/two-fa-auth' => 'staff/two-fa-auth.php',
    'staff/dashboard' => 'staff/dashboard.php',
    'staff/logout' => 'staff/logout.php',
    'staff/forgot-password' => 'staff/forgot-password.php',
];

// Check if the route exists
if (isset($routes[$path])) {
    require $routes[$path];
    return true;
}

// If it's a direct file request, serve it normally
if (file_exists($path) && !is_dir($path)) {
    return false; // Let PHP serve the file
}

// If it's a directory, check for index.php
if (is_dir($path)) {
    if (file_exists($path . '/index.php')) {
        require $path . '/index.php';
        return true;
    }
}
// Check for .php extension
if (file_exists($path . '.php')) {
    require $path . '.php';
    return true;
}

// Default to index.php for root
if ($path === '' || $path === 'index') {
    require 'index.php';
    return true;
}

// 404 - file not found
http_response_code(404);
echo "404 - Page not found";
return true;
?>

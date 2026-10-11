<?php
/**
 * Router script for PHP Built-in Server (Railway / Nixpacks / Local Dev)
 * Handles static asset serving and forwards /api routes to /api/index.php
 */

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Convenience redirects for admin and customer dashboards
if ($uri === '/admin' || $uri === '/admin/') {
    header('Location: /admin/dashboard.html');
    exit;
}
if ($uri === '/customer' || $uri === '/customer/') {
    header('Location: /customer/dashboard.html');
    exit;
}

// Forward all API requests directly to the API router
if (preg_match('#^/api(?:/|$)#i', $uri)) {
    require __DIR__ . '/api/index.php';
    exit;
}

// Check for static files on disk
$filePath = __DIR__ . $uri;

if ($uri !== '/' && file_exists($filePath) && !is_dir($filePath)) {
    // Returning false instructs PHP built-in server to serve the static file natively with correct MIME types
    return false;
}

// Serve root landing page
if ($uri === '/' || $uri === '') {
    include __DIR__ . '/index.html';
    exit;
}

// 404 fallback
http_response_code(404);
echo "404 Not Found";

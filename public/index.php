<?php

require __DIR__ . '/../vendor/autoload.php';

use NewsAggregator\Controllers\ArticleController;

// Enable CORS
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

// Simple router
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Initialize controllers
$articleController = new ArticleController();

// Route handling
try {
    $response = match (true) {
        $uri === '/api/articles' && $method === 'GET' => 
            $articleController->index($_GET['page'] ?? 1),
            
        $uri === '/api/articles/search' && $method === 'GET' => 
            $articleController->search($_GET['q'], $_GET['page'] ?? 1),
            
        $uri === '/api/articles/fetch' && $method === 'POST' => 
            $articleController->fetchLatest(),
            
        str_starts_with($uri, '/api/articles/') && $method === 'GET' => 
            $articleController->show(explode('/', $uri)[3]),
            
        default => [
            'status' => 'error',
            'message' => 'Route not found'
        ]
    };
} catch (\Exception $e) {
    $response = [
        'status' => 'error',
        'message' => $e->getMessage()
    ];
}

echo json_encode($response); 
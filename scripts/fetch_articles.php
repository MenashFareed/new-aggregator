<?php

require __DIR__ . '/../vendor/autoload.php';

use NewsAggregator\Controllers\ArticleController;

$controller = new ArticleController();
$result = $controller->fetchLatest();

echo json_encode($result) . "\n"; 
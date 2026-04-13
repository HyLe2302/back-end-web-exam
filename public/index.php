<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\Model;
use Dotenv\Dotenv;
use Pecee\SimpleRouter\SimpleRouter as Router;

$url = $_SERVER['REQUEST_URI'];

if ($url !== '/' && str_ends_with($url, '/')) {
    $newUrl = rtrim($url, '/');
    header("Location: $newUrl");
    exit;
}

// load env
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// init database
new Model();

// router
Router::setDefaultNamespace('App\\Controllers');

// load routes
require_once __DIR__ . '/../src/routes/api.php';

Router::get('/', function () {
    echo "API running...";
});

Router::start();
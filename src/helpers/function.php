<?php

use Pecee\Http\Request;
use Pecee\Http\Url;
use Pecee\SimpleRouter\SimpleRouter as Router;

function request(): Request {
    return Router::request();
}

function url(): Url {
    return Router::request()->getUrl();
}

function jsonResponse($data = [], int $status = 200) {
    http_response_code($status);
    header('Content-Type: application/json');

    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

if (!function_exists('env')) {
    function env($key, $default = null) {
        return $_ENV[$key] ?? $default;
    }
}

function basePath($path = '') {
    return dirname(__DIR__, 2) . ($path ? '/' . $path : '');
}

function storagePath($path = '') {
    return basePath('storage' . ($path ? '/' . $path : ''));
}

function input($key, $default = null) {
    return request()->getInputHandler()->value($key, $default);
}

function param($key) {
    return request()->getInputHandler()->value($key);
}
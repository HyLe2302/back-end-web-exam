<?php

function successResponse($data = [], $status = 200, $meta = []) {
    http_response_code($status);
    header('Content-Type: application/json');

    echo json_encode([
        'status' => $status,
        'message' => 'Success',
        'data' => $data,
        'meta' => $meta
    ]);
    exit;
}

function errorResponse($status = 500, $message = 'Error', $errors = [], $meta = []) {
    http_response_code($status);
    header('Content-Type: application/json');

    echo json_encode([
        'status' => $status,
        'message' => $message,
        'errors' => $errors,
        'meta' => $meta
    ]);
    exit;
}
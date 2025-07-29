<?php

function show404()
{
    http_response_code(404);
    require_once __DIR__ . '/../views/Errors/404.php';
    exit;
}

function show500()
{
    http_response_code(500);
    require_once __DIR__ . '/../Views/Errors/500.php';
    exit;
}

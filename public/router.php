<?php

$path = $_SERVER['DOCUMENT_ROOT'] . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if (is_file($path)) {
    return false;
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/index.php';

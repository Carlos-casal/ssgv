<?php
$uri = explode('?', $_SERVER['REQUEST_URI'])[0];
if (is_file(__DIR__ . '/public' . $uri)) {
    return false;
}
$_SERVER['SCRIPT_NAME'] = '/index.php';
require_once __DIR__ . '/public/index.php';

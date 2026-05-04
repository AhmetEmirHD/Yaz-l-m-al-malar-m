<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Sahte environment hazırlayalım ki requireLogin çalışmasın veya çalışsa da geçsin
$_SERVER['HTTP_HOST'] = 'localhost';
$_SERVER['REQUEST_URI'] = '/tuzlateknik/books/index.php';
$_SERVER['REQUEST_METHOD'] = 'GET';

session_start();
$_SESSION['user_id'] = 1; // sahte giriş
$_SESSION['role'] = 'super_admin';

try {
    require 'c:\xampp\htdocs\tuzlateknik\books\index.php';
}
catch (Throwable $e) {
    echo "HATA YAKALANDI: " . $e->getMessage() . " (Satır: " . $e->getLine() . ")";
}

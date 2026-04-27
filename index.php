<?php
/**
 * Main Index - Loads Active Theme
 */

session_start();
require_once __DIR__ . '/includes/functions.php';

// Get active theme
$activeTheme = getActiveTheme();

if (!$activeTheme) {
    // No active theme, show default message
    die('<h1 style="text-align:center; margin-top:100px; color:#667eea;">Aktif Tema Bulunamadı</h1><p style="text-align:center;">Admin panelinden bir tema aktif edin.</p>');
}

$themeFolder = $activeTheme['folder'];
$themePath = THEMES_DIR . '/' . $themeFolder;

// Check if theme exists
if (!is_dir($themePath)) {
    die('<h1 style="text-align:center; margin-top:100px; color:#dc3545;">Tema Klasörü Bulunamadı</h1><p style="text-align:center;">Tema: ' . $themeFolder . '</p>');
}

// Load theme
$layoutFile = $themePath . '/layout.php';
$themeConfig = getThemeConfig($themeFolder);

if (!file_exists($layoutFile)) {
    die('<h1 style="text-align:center; margin-top:100px; color:#dc3545;">Tema layout.php Dosyası Bulunamadı</h1>');
}

// Load theme functions if exists
$themeFunctions = $themePath . '/functions.php';
if (file_exists($themeFunctions)) {
    include_once $themeFunctions;
}

// Include theme layout
include_once $layoutFile;
?>

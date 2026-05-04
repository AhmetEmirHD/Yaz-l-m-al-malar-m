<?php
// Test script to check books setup
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';

echo "<h2>Books System Test</h2>";

// Test 1: Check if books table exists
try {
    $stmt = db()->query("SHOW TABLES LIKE 'books'");
    if ($stmt->rowCount() > 0) {
        echo "<p style='color: green;'>✓ Books table exists</p>";
    } else {
        echo "<p style='color: red;'>✗ Books table does NOT exist!</p>";
        echo "<p><strong>Action Required:</strong> Run the SQL file: database/books.sql</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Error checking table: " . htmlspecialchars($e->getMessage()) . "</p>";
}

// Test 2: Check uploads directory
$uploadDir = UPLOADS_PATH . '/books/';
if (is_dir($uploadDir)) {
    echo "<p style='color: green;'>✓ Upload directory exists: {$uploadDir}</p>";
} else {
    echo "<p style='color: orange;'>⚠ Upload directory does not exist. Will be created on first upload.</p>";
}

// Test 3: Check MAX_FILE_SIZE
echo "<p style='color: blue;'>ℹ MAX_FILE_SIZE: " . number_format(MAX_FILE_SIZE / (1024 * 1024), 0) . "MB</p>";

// Test 4: Check if user is admin
if (isLoggedIn()) {
    $user = currentUser();
    echo "<p style='color: green;'>✓ Logged in as: " . htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) . " (" . htmlspecialchars($user['role_name']) . ")</p>";
    
    if (hasRole(['super_admin', 'admin'])) {
        echo "<p style='color: green;'>✓ User has admin privileges - can upload books</p>";
    } else {
        echo "<p style='color: orange;'>⚠ User is not an admin - cannot upload books</p>";
    }
} else {
    echo "<p style='color: red;'>✗ Not logged in</p>";
}

echo "<hr>";
echo "<p><a href='books/index.php'>Go to Books Page</a></p>";
?>

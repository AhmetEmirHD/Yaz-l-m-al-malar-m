<?php
// Hızlı test - Admin kontrolü
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';

echo "<h2>Admin Kontrolü</h2>";

if (isLoggedIn()) {
    $user = currentUser();
    echo "<p style='color: green;'>✓ Giriş yapıldı</p>";
    echo "<p><strong>Kullanıcı:</strong> " . htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) . "</p>";
    echo "<p><strong>Rol:</strong> " . htmlspecialchars($user['role_name']) . "</p>";
    
    if (hasRole(['super_admin', 'admin'])) {
        echo "<p style='color: green; font-weight: bold;'>✓ ADMIN yetkisi var!</p>";
    } else {
        echo "<p style='color: red; font-weight: bold;'>✗ ADMIN yetkisi YOK!</p>";
        echo "<p>Sadece super_admin veya admin rolündeki kullanıcılar kitap yükleyebilir.</p>";
    }
} else {
    echo "<p style='color: red; font-weight: bold;'>✗ Oturum bulunamadı!</p>";
    echo "<p>Lütfen önce giriş yapın.</p>";
}

echo "<hr>";
echo "<p><a href='/tuzlateknik/books/index.php'>Kitaplar sayfasına dön</a></p>";
?>

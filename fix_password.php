<?php
/**
 * Fix Admin Password
 */

require_once __DIR__ . '/config/database.php';

echo "<h2>Admin Şifre Düzeltme</h2>";

try {
    $db = getDB();
    
    // Generate new hash for admin123
    $newPassword = 'admin123';
    $newHash = password_hash($newPassword, PASSWORD_DEFAULT);
    
    echo "<p>Yeni Hash: " . $newHash . "</p>";
    echo "<p>Test: " . (password_verify($newPassword, $newHash) ? '✓ Başarılı' : '✗ Başarısız') . "</p>";
    
    // Update admin password
    $stmt = $db->prepare("UPDATE admin_users SET password = ? WHERE username = ?");
    $stmt->execute([$newHash, 'admin']);
    
    echo "<p style='color:green;'>✓ Admin şifresi güncellendi</p>";
    echo "<p>Kullanıcı: admin</p>";
    echo "<p>Şifre: admin123</p>";
    
    echo "<p><a href='admin/login.php'>Giriş Yap</a></p>";
    
} catch (PDOException $e) {
    echo "<p style='color:red;'>✗ Hata: " . $e->getMessage() . "</p>";
}
?>

<?php
/**
 * Database Check Script
 */

require_once __DIR__ . '/config/database.php';

echo "<h2>Veritabanı Kontrolü</h2>";

try {
    $db = getDB();
    echo "<p style='color:green;'>✓ Veritabanı bağlantısı başarılı</p>";
    
    // Check tables
    $tables = $db->query("SHOW TABLES")->fetchAll();
    echo "<p>Tablolar: " . count($tables) . "</p>";
    
    // Check admin users
    $stmt = $db->query("SELECT * FROM admin_users");
    $users = $stmt->fetchAll();
    echo "<p>Admin Kullanıcıları: " . count($users) . "</p>";
    
    if (empty($users)) {
        echo "<p style='color:red;'>✓ Admin kullanıcısı bulunamadı!</p>";
        
        // Create admin user
        $password = password_hash('admin123', PASSWORD_DEFAULT);
        $stmt = $db->prepare("INSERT INTO admin_users (username, password, email, full_name) VALUES (?, ?, ?, ?)");
        $stmt->execute(['admin', $password, 'admin@example.com', 'Super Admin']);
        echo "<p style='color:green;'>✓ Admin kullanıcısı oluşturuldu (admin/admin123)</p>";
    } else {
        foreach ($users as $user) {
            echo "<p>- " . $user['username'] . " (" . $user['full_name'] . ") - Aktif: " . ($user['is_active'] ? 'Evet' : 'Hayır') . "</p>";
        }
    }
    
    // Check themes
    $stmt = $db->query("SELECT * FROM themes");
    $themes = $stmt->fetchAll();
    echo "<p>Temalar: " . count($themes) . "</p>";
    
} catch (PDOException $e) {
    echo "<p style='color:red;'>✗ Hata: " . $e->getMessage() . "</p>";
}
?>

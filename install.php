<?php
/**
 * Installation Script
 * Multi-Theme System
 */

require_once __DIR__ . '/config/database.php';

$errors = [];
$success = false;

// Step 1: Create database
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";charset=" . DB_CHARSET,
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    $pdo->exec("CREATE DATABASE IF NOT EXISTS " . DB_NAME . " CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    
} catch (PDOException $e) {
    $errors[] = 'Veritabanı oluşturma hatası: ' . $e->getMessage();
}

// Step 2: Connect to database and run SQL
if (empty($errors)) {
    try {
        $db = getDB();
        
        // Read SQL file
        $sqlFile = __DIR__ . '/sql/database.sql';
        if (file_exists($sqlFile)) {
            $sql = file_get_contents($sqlFile);
            
            // Split SQL by semicolon
            $statements = explode(';', $sql);
            
            foreach ($statements as $statement) {
                $statement = trim($statement);
                if (!empty($statement)) {
                    $db->exec($statement);
                }
            }
        }
        
        // Step 3: Register locksmith theme in database
        $themeFolder = 'locksmith-theme';
        $themeConfig = json_decode(file_get_contents(THEMES_DIR . '/' . $themeFolder . '/theme.json'), true);
        
        if ($themeConfig) {
            $stmt = $db->prepare("SELECT id FROM themes WHERE folder = ?");
            $stmt->execute([$themeFolder]);
            
            if (!$stmt->fetch()) {
                $stmt = $db->prepare("INSERT INTO themes (name, description, folder, version, is_active) VALUES (?, ?, ?, ?, 1)");
                $stmt->execute([
                    $themeConfig['name'],
                    $themeConfig['description'] ?? '',
                    $themeFolder,
                    $themeConfig['version'] ?? '1.0.0'
                ]);
            }
        }
        
        $success = true;
        
    } catch (PDOException $e) {
        $errors[] = 'SQL çalıştırma hatası: ' . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kurulum | <?php echo APP_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .install-card {
            background: white;
            border-radius: 20px;
            padding: 3rem;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 600px;
        }
    </style>
</head>
<body>
    <div class="install-card">
        <div class="text-center mb-4">
            <i class="fas fa-layer-group fa-4x" style="color: #667eea;"></i>
            <h3 class="mt-3"><?php echo APP_NAME; ?></h3>
            <p class="text-muted">Kurulum Sihirbazı</p>
        </div>
        
        <?php if ($success): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> Kurulum başarıyla tamamlandı!
            </div>
            
            <div class="card mb-4">
                <div class="card-body">
                    <h5><i class="fas fa-info-circle"></i> Bilgiler</h5>
                    <ul class="mb-0">
                        <li><strong>Admin Panel:</strong> <a href="admin/login.php">admin/login.php</a></li>
                        <li><strong>Kullanıcı Adı:</strong> admin</li>
                        <li><strong>Şifre:</strong> admin123</li>
                        <li><strong>Site:</strong> <a href="index.php">index.php</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="d-grid gap-2">
                <a href="admin/login.php" class="btn btn-primary btn-lg">
                    <i class="fas fa-tachometer-alt"></i> Admin Panele Git
                </a>
                <a href="index.php" class="btn btn-outline-primary">
                    <i class="fas fa-home"></i> Siteyi Görüntüle
                </a>
            </div>
            
        <?php else: ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i> Kurulum hatası oluştu:
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            
            <div class="card mb-4">
                <div class="card-body">
                    <h5><i class="fas fa-database"></i> Veritabanı Ayarları</h5>
                    <p class="mb-1"><strong>Host:</strong> <?php echo DB_HOST; ?></p>
                    <p class="mb-1"><strong>Database:</strong> <?php echo DB_NAME; ?></p>
                    <p class="mb-1"><strong>User:</strong> <?php echo DB_USER; ?></p>
                    <p class="mb-0"><strong>Password:</strong> <?php echo DB_PASS ? '***' : '(boş)'; ?></p>
                </div>
            </div>
            
            <div class="d-grid">
                <button onclick="location.reload()" class="btn btn-primary">
                    <i class="fas fa-redo"></i> Tekrar Dene
                </button>
            </div>
        <?php endif; ?>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

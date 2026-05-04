<?php
/**
 * Landing Page - Blog & Duyurular
 * Tuzla Mesleki ve Teknik Anadolu Lisesi
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';

// Eğer giriş yapmışsa dashboard'a yönlendir
if (isset($_SESSION['user_id'])) {
    header('Location: ' . SITE_URL . '/dashboard');
    exit;
}

// Son 5 duyuruyu çek (herkes için görünür olanlar)
$stmt = db()->prepare("
    SELECT a.*, u.first_name, u.last_name, u.avatar
    FROM announcements a
    LEFT JOIN users u ON a.author_id = u.id
    WHERE a.is_published = 1
    ORDER BY a.created_at DESC
    LIMIT 5
");
$stmt->execute();
$announcements = $stmt->fetchAll();

// Son testleri çek (genel bilgi için)
$stmt = db()->prepare("
    SELECT t.*, s.name as subject_name, s.color
    FROM tests t
    LEFT JOIN subjects s ON t.subject_id = s.id
    WHERE t.is_active = 1
    ORDER BY t.created_at DESC
    LIMIT 3
");
$stmt->execute();
$recentTests = $stmt->fetchAll();

?>
<!DOCTYPE html>
<html lang="tr" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tuzla Mesleki ve Teknik Anadolu Lisesi - Ana Sayfa</title>
    
    <!-- Phosphor Icons -->
    <link rel="stylesheet" href="https://unpkg.com/@phosphor-icons/web@2.0.3/src/regular/style.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- CSS -->
    <link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/landing.css">
</head>
<body class="landing-page">

    <!-- Landing Header -->
    <header class="landing-header">
        <div class="container">
            <div class="header-content">
                <!-- Logo & Title -->
                <a href="<?= SITE_URL ?>" class="header-brand">
                    <div class="brand-logo">
                        <i class="ph ph-graduation-cap"></i>
                    </div>
                    <div class="brand-text">
                        <span class="brand-name">Tuzla Mesleki ve Teknik</span>
                        <span class="brand-subtitle">Anadolu Lisesi</span>
                    </div>
                </a>
                
                <!-- Center: Search -->
                <div class="header-center">
                    <div class="header-search">
                        <i class="ph ph-magnifying-glass"></i>
                        <input type="text" placeholder="Duyuru, test veya içerik ara..." id="search-input">
                        <kbd class="search-shortcut">Ctrl+K</kbd>
                    </div>
                </div>
                
                <!-- Right: Actions -->
                <div class="header-actions">
                    <!-- Date/Time -->
                    <div class="header-datetime">
                        <i class="ph ph-calendar-blank"></i>
                        <div class="datetime-text">
                            <span class="time" id="current-time">--:--</span>
                            <span class="date" id="current-date">--</span>
                        </div>
                    </div>
                    
                    <!-- Theme Toggle -->
                    <button class="theme-toggle" id="themeToggle" title="Tema Değiştir">
                        <i class="ph ph-sun theme-icon-light"></i>
                        <i class="ph ph-moon theme-icon-dark"></i>
                    </button>
                    
                    <!-- Auth Buttons -->
                    <div class="auth-buttons">
                        <a href="<?= SITE_URL ?>/auth/login.php" class="btn-auth btn-login">
                            <i class="ph ph-sign-in"></i>
                            <span>Giriş Yap</span>
                        </a>
                        <a href="<?= SITE_URL ?>/auth/register.php" class="btn-auth btn-register">
                            <i class="ph ph-user-plus"></i>
                            <span>Üye Ol</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section with Slider -->
    <section class="hero-section">
        <div class="container">
            <div class="hero-grid">
                <!-- Main Slider -->
                <div class="slider-main">
                    <div class="slider-wrapper">
                        <?php if (!empty($announcements)): ?>
                            <?php foreach ($announcements as $index => $announcement): ?>
                                <div class="slide <?= $index === 0 ? 'active' : '' ?>" data-slide="<?= $index ?>">
                                    <div class="slide-image-wrapper">
                                        <?php if ($announcement['image']): ?>
                                            <img src="<?= UPLOADS_URL ?>/announcements/<?= htmlspecialchars($announcement['image']) ?>" 
                                                 alt="<?= htmlspecialchars($announcement['title']) ?>" class="slide-bg">
                                        <?php else: ?>
                                            <div class="slide-bg slide-placeholder">
                                                <i class="ph ph-megaphone"></i>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <!-- Slider Controls -->
                                        <button class="slider-control prev" onclick="window.slider && window.slider.prevSlide()">
                                            <i class="ph ph-caret-left"></i>
                                        </button>
                                        <button class="slider-control next" onclick="window.slider && window.slider.nextSlide()">
                                            <i class="ph ph-caret-right"></i>
                                        </button>
                                        
                                        <!-- Slider Indicators -->
                                        <div class="slider-indicators">
                                            <?php foreach ($announcements as $idx => $ann): ?>
                                                <button class="indicator <?= $idx === 0 ? 'active' : '' ?>" 
                                                        data-slide="<?= $idx ?>"></button>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                    
                                    <div class="slide-content">
                                        <span class="slide-badge">
                                            <i class="ph ph-megaphone"></i>
                                            Duyuru
                                        </span>
                                        <h2 class="slide-title"><?= htmlspecialchars($announcement['title']) ?></h2>
                                        <?php if (!empty($announcement['content'])): ?>
                                            <p class="slide-desc"><?= htmlspecialchars(mb_substr(strip_tags($announcement['content']), 0, 200)) ?><?= mb_strlen($announcement['content']) > 200 ? '...' : '' ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="slide active">
                                <div class="slide-image-wrapper">
                                    <div class="slide-bg slide-placeholder">
                                        <i class="ph ph-graduation-cap"></i>
                                    </div>
                                </div>
                                <div class="slide-content">
                                    <span class="slide-badge">
                                        <i class="ph ph-info"></i>
                                        Hoş Geldiniz
                                    </span>
                                    <h2 class="slide-title">Tuzla Mesleki ve Teknik Anadolu Lisesi</h2>
                                    <p class="slide-desc">Modern eğitim platformumuzla bilgiye erişimin en kolay yolu. Hemen kayıt olun ve öğrenmeye başlayın!</p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Thumbnail Navigation -->
                <div class="slider-nav">
                    <h3 class="nav-title">
                        <i class="ph ph-list-bullets"></i>
                        Duyurular
                    </h3>
                    <div class="nav-items">
                        <?php if (!empty($announcements)): ?>
                            <?php foreach ($announcements as $index => $announcement): ?>
                                <div class="nav-item <?= $index === 0 ? 'active' : '' ?>" data-slide="<?= $index ?>">
                                    <?php if ($announcement['image']): ?>
                                        <div class="nav-thumb">
                                            <img src="<?= UPLOADS_URL ?>/announcements/<?= htmlspecialchars($announcement['image']) ?>" 
                                                 alt="<?= htmlspecialchars($announcement['title']) ?>">
                                        </div>
                                    <?php else: ?>
                                        <div class="nav-thumb nav-thumb-placeholder">
                                            <i class="ph ph-megaphone"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div class="nav-content">
                                        <h4 class="nav-item-title"><?= htmlspecialchars(mb_substr($announcement['title'], 0, 40)) ?><?= mb_strlen($announcement['title']) > 40 ? '...' : '' ?></h4>
                                        <span class="nav-date">
                                            <i class="ph ph-calendar"></i>
                                            <?= date('d.m.Y', strtotime($announcement['created_at'])) ?>
                                        </span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Recent Tests Section -->
    <section class="recent-tests-section">
        <div class="container">
            <h2 class="section-title">
                <i class="ph ph-exam"></i> Son Eklenen Testler
            </h2>
            <div class="tests-grid">
                <?php if (!empty($recentTests)): ?>
                    <?php foreach ($recentTests as $test): ?>
                        <div class="test-card">
                            <div class="test-subject" style="background: <?= $test['color'] ?>20; color: <?= $test['color'] ?>">
                                <?= htmlspecialchars($test['subject_name']) ?>
                            </div>
                            <h3 class="test-title"><?= htmlspecialchars($test['title']) ?></h3>
                            <div class="test-info">
                                <span><i class="ph ph-question"></i> <?= $test['total_questions'] ?> Soru</span>
                                <span><i class="ph ph-clock"></i> <?= $test['duration_minutes'] ?> Dk</span>
                            </div>
                            <a href="<?= SITE_URL ?>/auth/login.php" class="test-login-btn">
                                Teste Başlamak İçin Giriş Yap
                                <i class="ph ph-arrow-right"></i>
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="ph ph-exam"></i>
                        <p>Henüz test eklenmemiş</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="landing-footer">
        <div class="container">
            <div class="footer-bottom">
                <p>&copy; <?= date('Y') ?> Tuzla Mesleki ve Teknik Anadolu Lisesi. Tüm hakları saklıdır.</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="<?= SITE_URL ?>/assets/js/landing.js"></script>

</body>
</html>

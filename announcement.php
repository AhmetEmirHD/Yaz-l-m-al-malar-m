<?php
/**
 * Duyuru Detay Sayfası
 * Tuzla Teknik Akademi
 */

$pageTitle = 'Duyuru';
require_once __DIR__ . '/includes/header.php';
requireLogin();

$announcementId = intval($_GET['id'] ?? 0);

if (!$announcementId) {
    setFlash('error', 'Duyuru bulunamadı.');
    redirect('/announcements');
}

// Duyuruyu çek
$stmt = db()->prepare("
    SELECT a.*, 
           u.first_name, u.last_name, u.avatar,
           r.display_name as author_role
    FROM announcements a
    LEFT JOIN users u ON a.author_id = u.id
    LEFT JOIN roles r ON u.role_id = r.id
    WHERE a.id = ?
");
$stmt->execute([$announcementId]);
$announcement = $stmt->fetch();

if (!$announcement) {
    setFlash('error', 'Duyuru bulunamadı.');
    redirect('/announcements');
}

// Görüntülenme sayısını artır
db()->prepare("UPDATE announcements SET views = views + 1 WHERE id = ?")->execute([$announcementId]);
$announcement['views']++;
?>

<div class="announcement-detail-page">
    <div class="page-header">
        <a href="<?= SITE_URL ?>/announcements" class="back-link">
            <i class="ph ph-arrow-left"></i> Duyurulara Dön
        </a>
    </div>
    
    <article class="announcement-card-detail">
        <?php if ($announcement['is_pinned']): ?>
            <div class="announcement-pin">
                <i class="ph ph-push-pin"></i> Sabitlenmiş Duyuru
            </div>
        <?php endif; ?>
        
        <header class="announcement-header">
            <img src="<?= avatarUrl($announcement['avatar']) ?>" alt="" class="announcement-avatar">
            <div class="announcement-meta">
                <span class="announcement-author">
                    <?= e($announcement['first_name'] . ' ' . $announcement['last_name']) ?>
                </span>
                <span class="announcement-info">
                    <?= e($announcement['author_role']) ?> • 
                    <?= date('d.m.Y H:i', strtotime($announcement['published_at'])) ?> •
                    <i class="ph ph-eye"></i> <?= $announcement['views'] ?> görüntüleme
                </span>
            </div>
            
            <?php if ($announcement['author_id'] == $_SESSION['user_id'] || hasRole(['super_admin'])): ?>
                <div class="announcement-actions">
                    <a href="<?= SITE_URL ?>/announcements/create.php?id=<?= $announcement['id'] ?>" 
                       class="btn btn-sm btn-secondary">
                        <i class="ph ph-pencil"></i> Düzenle
                    </a>
                    <a href="javascript:void(0)" 
                       class="btn btn-sm btn-danger"
                       data-confirm="true"
                       data-confirm-title="Duyuru Sil"
                       data-confirm-message="Bu duyuruyu silmek istediğinize emin misiniz?"
                       data-confirm-type="danger"
                       data-confirm-text="Sil"
                       data-href="<?= SITE_URL ?>/announcements?delete=<?= $announcement['id'] ?>">
                        <i class="ph ph-trash"></i> Sil
                    </a>
                </div>
            <?php endif; ?>
        </header>
        
        <h1 class="announcement-title"><?= e($announcement['title']) ?></h1>
        
        <?php if ($announcement['image']): ?>
            <div class="announcement-image-wrapper">
                <img src="<?= UPLOADS_URL ?>/announcements/<?= e($announcement['image']) ?>" 
                     alt="<?= e($announcement['title']) ?>" 
                     class="announcement-image">
            </div>
        <?php endif; ?>
        
        <div class="announcement-content">
            <?= nl2br(e($announcement['content'])) ?>
        </div>
        
        <footer class="announcement-footer">
            <span class="announcement-date">
                Yayınlanma: <?= date('d.m.Y H:i', strtotime($announcement['published_at'])) ?>
            </span>
            <?php if ($announcement['target_role']): ?>
                <span class="announcement-target">
                    <i class="ph ph-users"></i> 
                    Hedef Kitle: <?= $announcement['target_role'] === 'ogrenci' ? 'Öğrenciler' : 'Öğretmenler' ?>
                </span>
            <?php endif; ?>
        </footer>
    </article>
</div>

<style>
.announcement-detail-page {
    max-width: 900px;
    margin: 0 auto;
    padding: 24px;
}

.back-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: var(--text-secondary);
    text-decoration: none;
    margin-bottom: 24px;
    font-size: 14px;
    transition: color var(--transition-fast);
}

.back-link:hover {
    color: var(--primary);
}

.announcement-card-detail {
    background: var(--bg-primary);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-lg);
    padding: 40px;
    box-shadow: var(--shadow-sm);
}

.announcement-pin {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 6px 12px;
    background: var(--primary-bg);
    color: var(--primary);
    border-radius: var(--radius-full);
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 20px;
}

.announcement-header {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 24px;
    padding-bottom: 24px;
    border-bottom: 1px solid var(--border-color);
}

.announcement-avatar {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    object-fit: cover;
}

.announcement-meta {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.announcement-author {
    font-size: 16px;
    font-weight: 600;
    color: var(--text-primary);
}

.announcement-info {
    font-size: 13px;
    color: var(--text-secondary);
    display: flex;
    align-items: center;
    gap: 8px;
}

.announcement-actions {
    display: flex;
    gap: 8px;
}

.announcement-title {
    font-size: 32px;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 32px;
    line-height: 1.3;
}

.announcement-image-wrapper {
    margin-bottom: 32px;
    border-radius: var(--radius-md);
    overflow: hidden;
}

.announcement-image {
    width: 100%;
    height: auto;
    display: block;
}

.announcement-content {
    font-size: 16px;
    line-height: 1.8;
    color: var(--text-primary);
    margin-bottom: 32px;
}

.announcement-footer {
    padding-top: 24px;
    border-top: 1px solid var(--border-color);
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    font-size: 14px;
    color: var(--text-muted);
}

.announcement-target {
    display: flex;
    align-items: center;
    gap: 6px;
}

/* Responsive */
@media (max-width: 768px) {
    .announcement-detail-page {
        padding: 16px;
    }
    
    .announcement-card-detail {
        padding: 24px;
    }
    
    .announcement-title {
        font-size: 24px;
    }
    
    .announcement-header {
        flex-wrap: wrap;
    }
    
    .announcement-actions {
        width: 100%;
        justify-content: flex-end;
    }
}
</style>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

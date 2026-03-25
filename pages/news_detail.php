<?php
    require_once '../includes/config.php';
    require_once '../includes/functions.php';

    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    $news = getNewsById($pdo, $id);

    if (!$news) {
        header('Location: news.php');
        exit;
    }

    include '../includes/header.php';
?>

<div class="page-header">
    <div class="container">
        <h1><?= sanitize($news['title']) ?></h1>
        <p><?= date('d.m.Y', strtotime($news['created_at'])) ?></p>
    </div>
</div>

<div class="container">
    <div class="program-detail">
        <?php if ($news['image']): ?>
            <img src="<?= SITE_URL ?>/assets/uploads/<?= sanitize($news['image']) ?>" alt="<?= sanitize($news['title']) ?>" style="max-width: 100%; border-radius: 20px; margin-bottom: 1.5rem;">
        <?php endif; ?>
        <div class="news-content">
            <?= nl2br(sanitize($news['content'])) ?>
        </div>
        <a href="news.php" class="btn-outline" style="margin-top: 1.5rem; display: inline-block;">← Назад к новостям</a>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
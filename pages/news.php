<?php
    require_once '../includes/config.php';
    require_once '../includes/functions.php';

    $news = getNews($pdo);

    include '../includes/header.php';
?>

<div class="page-header">
    <div class="container">
        <h1>Новости Учебного центра</h1>
        <p>События, анонсы, достижения</p>
    </div>
</div>

<div class="container">
    <div class="cards-grid">
        <?php foreach ($news as $item): ?>
            <div class="card">
                <div class="card-image">
                    <?php if ($item['image']): ?>
                        <img src="<?= SITE_URL ?>/assets/uploads/<?= sanitize($item['image']) ?>" alt="<?= sanitize($item['title']) ?>">
                    <?php else: ?>
                        <div class="card-image-placeholder">📰</div>
                    <?php endif; ?>
                </div>
                <div class="card-content">
                    <div class="date"><?= date('d.m.Y', strtotime($item['created_at'])) ?></div>
                    <h3><?= sanitize($item['title']) ?></h3>
                    <p><?= sanitize(mb_substr(strip_tags($item['content']), 0, 120)) ?>...</p>
                    <a href="news_detail.php?id=<?= $item['id'] ?>" class="card-link">Читать подробнее →</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
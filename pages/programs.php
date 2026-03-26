<?php
    require_once '../includes/config.php';
    require_once '../includes/functions.php';

    $programs = getPrograms($pdo);

    include '../includes/header.php';
?>

<div class="page-header">
    <div class="container">
        <h1>Образовательные программы</h1>
        <p>Выберите курс для профессионального роста</p>
    </div>
</div>

<div class="container">
    <div class="cards-grid">
        <?php foreach ($programs as $program): ?>
            <div class="card">
                <div class="card-image">
                    <?php if ($program['image']): ?>
                        <img src="<?= SITE_URL ?>/assets/uploads/<?= sanitize($program['image']) ?>" alt="<?= sanitize($program['title']) ?>">
                    <?php else: ?>
                        <div class="card-image-placeholder"></div>
                    <?php endif; ?>
                </div>
                <div class="card-content">
                    <h3><?= sanitize($program['title']) ?></h3>
                    <p><strong>Длительность:</strong> <?= sanitize($program['duration']) ?><br>
                    <strong>Требования:</strong> <?= sanitize($program['requirements']) ?><br>
                    <?= sanitize(mb_substr($program['description'], 0, 80)) ?>...</p>
                    <a href="program_detail.php?id=<?= $program['id'] ?>" class="card-link">Подробнее →</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
<?php
    require_once '../includes/config.php';
    require_once '../includes/functions.php';

    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    $program = getProgramById($pdo, $id);

    if (!$program) {
        header('Location: programs.php');
        exit;
    }

    include '../includes/header.php';
?>

<div class="page-header">
    <div class="container">
        <h1><?= sanitize($program['title']) ?></h1>
    </div>
</div>

<div class="container">
    <div class="program-detail">
        <?php if ($program['image']): ?>
            <img src="<?= SITE_URL ?>/assets/uploads/<?= sanitize($program['image']) ?>" alt="<?= sanitize($program['title']) ?>" style="max-width: 100%; border-radius: 20px; margin-bottom: 1.5rem;">
        <?php endif; ?>
        
        <p><strong>Длительность:</strong> <?= sanitize($program['duration']) ?></p>
        <p><strong>Требования к слушателям:</strong> <?= sanitize($program['requirements']) ?></p>
        <p><strong>Учебный план:</strong> <?= sanitize($program['curriculum']) ?></p>
        <p><strong>Описание программы:</strong></p>
        <p><?= nl2br(sanitize($program['description'])) ?></p>
        <p><strong>Документ об окончании:</strong> Удостоверение о повышении квалификации / Диплом о профессиональной переподготовке.</p>
        <a href="programs.php" class="btn-outline" style="margin-top: 1rem; display: inline-block;">← Назад к программам</a>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
<?php
    require_once 'includes/config.php';
    require_once 'includes/functions.php';

    $latestNews = getNews($pdo, 3);
    $programs = getPrograms($pdo);

    include 'includes/header.php';
?>

<section class="hero">
    <div class="container">
        <h1>Повышение квалификации<br>и профессиональное развитие</h1>
        <p>Учебный центр АО «НПО Курганприбор» — современные образовательные программы для специалистов промышленности. Подготовка, переподготовка и семинары.</p>
        <a href="<?= SITE_URL ?>/pages/programs.php" class="btn">Все программы →</a>
    </div>
</section>

<section class="about-organization">
    <div class="container">
        <div class="about-content">
            <h2 class="about-org-title">Об организации</h2>
            <div class="about-org-text">
                <p>Учебный центр АО «НПО Курганприбор» осуществляет подготовку, переподготовку и повышение квалификации для своих сотрудников. Мы предоставляем современные образовательные программы по контрольно-измерительным приборам и автоматике, метрологии, автоматизации технологических процессов и цифровой трансформации производства, обеспечивая высокий уровень профессиональной подготовки обучающихся.</p>
            </div>
        </div>
    </div>
</section>

<div class="container">
    <section style="margin: 4rem 0;">
        <div class="section-title">
            <h2>Ключевые направления обучения</h2>
        </div>
        <div class="cards-grid">
            <?php foreach (array_slice($programs, 0, 3) as $program): ?>
                <div class="card">
                    <div class="card-image">
                        <?php if ($program['image']): ?>
                            <img src="<?= SITE_URL ?>/assets/uploads/<?= sanitize($program['image']) ?>" alt="<?= sanitize($program['title']) ?>">
                        <?php else: ?>
                            <div class="card-image-placeholder">📘</div>
                        <?php endif; ?>
                    </div>
                    <div class="card-content">
                        <div class="tag">Образовательная программа</div>
                        <h3><?= sanitize($program['title']) ?></h3>
                        <p><?= sanitize(mb_substr($program['description'], 0, 100)) ?>...</p>
                        <a href="<?= SITE_URL ?>/pages/program_detail.php?id=<?= $program['id'] ?>" class="card-link">Подробнее →</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <section style="margin: 4rem 0;">
        <div class="section-title">
            <h2>Последние новости</h2>
        </div>
        <div class="cards-grid">
            <?php foreach ($latestNews as $news): ?>
                <div class="card">
                    <div class="card-image">
                        <?php if ($news['image']): ?>
                            <img src="<?= SITE_URL ?>/assets/uploads/<?= sanitize($news['image']) ?>" alt="<?= sanitize($news['title']) ?>">
                        <?php else: ?>
                            <div class="card-image-placeholder"></div>
                        <?php endif; ?>
                    </div>
                    <div class="card-content">
                        <div class="date"><?= date('d.m.Y', strtotime($news['created_at'])) ?></div>
                        <h3><?= sanitize($news['title']) ?></h3>
                        <p><?= sanitize(mb_substr(strip_tags($news['content']), 0, 100)) ?>...</p>
                        <a href="<?= SITE_URL ?>/pages/news_detail.php?id=<?= $news['id'] ?>" class="card-link">Читать →</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div style="text-align: center; margin-top: 1rem;">
            <a href="<?= SITE_URL ?>/pages/news.php" class="btn-outline" style="padding: 0.7rem 2rem;">Все новости</a>
        </div>
    </section>
</div>

<?php include 'includes/footer.php'; ?>
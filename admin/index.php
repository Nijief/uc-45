<?php
    require_once 'auth_check.php';
    include '../includes/header.php';
?>

<div class="container">
    <div class="page-header" style="background: transparent; padding: 1rem 0;">
        <h1>Панель управления</h1>
    </div>
    
    <div class="admin-menu" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem; margin: 2rem 0;">
        <div class="card" style="text-align: center; padding: 2rem;">
            <div style="font-size: 3rem; margin-bottom: 1rem;">📰</div>
            <h3>Управление новостями</h3>
            <p>Добавление, редактирование, удаление новостей</p>
            <a href="news.php" class="btn" style="margin-top: 1rem;">Перейти</a>
        </div>
        <div class="card" style="text-align: center; padding: 2rem;">
            <div style="font-size: 3rem; margin-bottom: 1rem;">📅</div>
            <h3>Управление расписанием</h3>
            <p>Добавление, редактирование, удаление записей расписания</p>
            <a href="schedule.php" class="btn" style="margin-top: 1rem;">Перейти</a>
        </div>
        <div class="card" style="text-align: center; padding: 2rem;">
            <div style="font-size: 3rem; margin-bottom: 1rem;">📚</div>
            <h3>Управление программами</h3>
            <p>Добавление, редактирование, удаление образовательных программ</p>
            <a href="programs.php" class="btn" style="margin-top: 1rem;">Перейти</a>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
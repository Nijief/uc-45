<?php
    require_once 'auth_check.php';
    include '../includes/header.php';
?>

<link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/admin.css">

<div class="admin-container">
    <div class="admin-dashboard">
        <div class="welcome-header">
            <h1>Панель управления</h1>
            <p>Управление контентом сайта</p>
        </div>
        
        <div class="admin-menu-grid">
            <div class="admin-menu-card">
                <div class="admin-menu-header">
                    <div class="admin-menu-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                        </svg>
                    </div>
                    <div>
                        <div class="admin-menu-title">Управление новостями</div>
                        <div class="admin-menu-description">Добавление, редактирование, удаление новостей</div>
                    </div>
                </div>
                <div class="admin-menu-content">
                    <a href="news.php" class="admin-menu-link">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                        Перейти к управлению
                    </a>
                </div>
            </div>
            
            <div class="admin-menu-card">
                <div class="admin-menu-header">
                    <div class="admin-menu-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <div class="admin-menu-title">Управление расписанием</div>
                        <div class="admin-menu-description">Добавление, редактирование, удаление записей расписания</div>
                    </div>
                </div>
                <div class="admin-menu-content">
                    <a href="schedule.php" class="admin-menu-link">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                        Перейти к управлению
                    </a>
                </div>
            </div>
            
            <div class="admin-menu-card">
                <div class="admin-menu-header">
                    <div class="admin-menu-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <div>
                        <div class="admin-menu-title">Управление программами</div>
                        <div class="admin-menu-description">Добавление, редактирование, удаление образовательных программ</div>
                    </div>
                </div>
                <div class="admin-menu-content">
                    <a href="programs.php" class="admin-menu-link">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                        Перейти к управлению
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
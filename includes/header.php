<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Учебный центр | АО «НПО Курганприбор»</title>
    <link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/style.css">
</head>
<body>

<header class="header">
    <div class="container">
        <div class="navbar">
            <div class="logo">
                <a href="<?= SITE_URL ?>/index.php">Учебный центр</a>
                <span>АО «НПО Курганприбор»</span>
            </div>
            <ul class="nav-menu">
                <li><a href="<?= SITE_URL ?>/index.php">Главная</a></li>
                <li><a href="<?= SITE_URL ?>/pages/programs.php">Программы</a></li>
                <li><a href="<?= SITE_URL ?>/pages/schedule.php">Расписание</a></li>
                <li><a href="<?= SITE_URL ?>/pages/news.php">Новости</a></li>
                <li><a href="<?= SITE_URL ?>/pages/contacts.php">Контакты</a></li>
                <?php if (isAdmin()): ?>
                    <li><a href="<?= SITE_URL ?>/admin/">Админ-панель</a></li>
                    <li><a href="<?= SITE_URL ?>/admin/logout.php">Выйти</a></li>
                <?php endif; ?>
            </ul>
            <div class="search-container">
                <form id="searchForm" class="search-form" action="#" method="get">
                    <div class="search-wrapper">
                        <input type="text" id="searchInput" class="search-input" placeholder="Поиск по сайту..." autocomplete="off">
                        <button type="submit" class="search-btn">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M21 21L16.65 16.65M19 11C19 15.4183 15.4183 19 11 19C6.58172 19 3 15.4183 3 11C3 6.58172 6.58172 3 11 3C15.4183 3 19 6.58172 19 11Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                    </div>
                </form>
                <div id="searchResults" class="search-results" style="display: none;"></div>
            </div>
        </div>
    </div>
</header>

<main>
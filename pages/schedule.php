<?php
    require_once '../includes/config.php';
    require_once '../includes/functions.php';

    $group = isset($_GET['group']) ? $_GET['group'] : 'all';
    $dateFrom = isset($_GET['date_from']) ? $_GET['date_from'] : '';
    $dateTo = isset($_GET['date_to']) ? $_GET['date_to'] : '';

    $schedule = getSchedule($pdo, $group, $dateFrom, $dateTo);

    include '../includes/header.php';
?>

<div class="page-header">
    <div class="container">
        <h1>Расписание занятий</h1>
        <p>Актуальное расписание на апрель 2026 года</p>
    </div>
</div>

<div class="container">
    <div class="filters-section">
        <div class="filters-title">
            <h3>Фильтры расписания</h3>
        </div>
        <form method="GET" action="" class="filters-grid">
            <div class="filter-group">
                <label for="group">Фильтр по группе:</label>
                <select name="group" id="group" class="filter-select">
                    <option value="all" <?= $group == 'all' ? 'selected' : '' ?>>Все группы</option>
                    <option value="Оператор ЧПУ-1 (дневная)" <?= $group == 'Оператор ЧПУ-1 (дневная)' ? 'selected' : '' ?>>Оператор ЧПУ-1 (дневная)</option>
                    <option value="Контролер ОТК-3 (вечерняя)" <?= $group == 'Контролер ОТК-3 (вечерняя)' ? 'selected' : '' ?>>Контролер ОТК-3 (вечерняя)</option>
                    <option value="Наладчик ЧПУ-5 (субботняя)" <?= $group == 'Наладчик ЧПУ-5 (субботняя)' ? 'selected' : '' ?>>Наладчик ЧПУ-5 (субботняя)</option>
                </select>
            </div>
            <div class="filter-group">
                <label for="date_from">Дата от:</label>
                <input type="date" name="date_from" id="date_from" class="filter-date" value="<?= sanitize($dateFrom) ?>">
            </div>
            <div class="filter-group">
                <label for="date_to">Дата до:</label>
                <input type="date" name="date_to" id="date_to" class="filter-date" value="<?= sanitize($dateTo) ?>">
            </div>
            <div class="filter-group filter-buttons">
                <button type="submit" class="btn-filter">Применить фильтры</button>
                <a href="schedule.php" class="btn-filter-reset">Сбросить</a>
            </div>
        </form>
    </div>

    <div class="schedule-wrapper">
        <table>
            <thead>
                <tr><th>Дата</th><th>Время</th><th>Группа</th><th>Предмет</th><th>Преподаватель</th></tr>
            </thead>
            <tbody>
                <?php if (count($schedule) > 0): ?>
                    <?php foreach ($schedule as $row): ?>
                        <tr>
                            <td><?= date('d.m.Y', strtotime($row['date'])) ?></td>
                            <td><?= date('H:i', strtotime($row['time'])) ?></td>
                            <td><?= sanitize($row['group_name']) ?></td>
                            <td><?= sanitize($row['subject']) ?></td>
                            <td><?= sanitize($row['teacher']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5" style="text-align: center;">По выбранным фильтрам занятий не найдено.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <p style="color:#6c757d; font-style:italic;">* Расписание обновляется еженедельно, возможны изменения.</p>
</div>

<?php include '../includes/footer.php'; ?>
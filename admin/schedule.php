<?php
    require_once 'auth_check.php';
    include '../includes/header.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_schedule'])) {
        $date = $_POST['date'];
        $time = $_POST['time'];
        $group_name = $_POST['group_name'];
        $subject = $_POST['subject'];
        $teacher = $_POST['teacher'];
        
        $stmt = $pdo->prepare("INSERT INTO schedule (date, time, group_name, subject, teacher) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$date, $time, $group_name, $subject, $teacher]);
        header('Location: schedule.php');
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_schedule'])) {
        $id = $_POST['id'];
        $date = $_POST['date'];
        $time = $_POST['time'];
        $group_name = $_POST['group_name'];
        $subject = $_POST['subject'];
        $teacher = $_POST['teacher'];
        
        $stmt = $pdo->prepare("UPDATE schedule SET date = ?, time = ?, group_name = ?, subject = ?, teacher = ? WHERE id = ?");
        $stmt->execute([$date, $time, $group_name, $subject, $teacher, $id]);
        header('Location: schedule.php');
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_schedule'])) {
        $id = $_POST['id'];
        $stmt = $pdo->prepare("DELETE FROM schedule WHERE id = ?");
        $stmt->execute([$id]);
        header('Location: schedule.php');
        exit;
    }

    $schedule = getSchedule($pdo);
?>

<link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/admin.css">

<div class="admin-container">
    <div class="admin-dashboard">
        <div class="admin-page-header">
            <h1>Управление расписанием</h1>
            <p>Добавление, редактирование и удаление записей расписания</p>
        </div>
        
        <div class="admin-form-section">
            <h2>Добавить занятие</h2>
            <form method="POST">
                <div class="admin-form-row">
                    <div class="admin-form-group">
                        <label>Дата</label>
                        <input type="date" name="date" required>
                    </div>
                    <div class="admin-form-group">
                        <label>Время</label>
                        <input type="time" name="time" required>
                    </div>
                </div>
                
                <div class="admin-form-row">
                    <div class="admin-form-group">
                        <label>Группа</label>
                        <select name="group_name" required>
                            <option value="Оператор ЧПУ-1 (дневная)">Оператор ЧПУ-1 (дневная)</option>
                            <option value="Контролер ОТК-3 (вечерняя)">Контролер ОТК-3 (вечерняя)</option>
                            <option value="Наладчик ЧПУ-5 (субботняя)">Наладчик ЧПУ-5 (субботняя)</option>
                        </select>
                    </div>
                    <div class="admin-form-group">
                        <label>Предмет</label>
                        <input type="text" name="subject" required>
                    </div>
                </div>
                
                <div class="admin-form-row">
                    <div class="admin-form-group">
                        <label>Преподаватель</label>
                        <input type="text" name="teacher" required>
                    </div>
                </div>
                
                <button type="submit" name="add_schedule" class="btn-save">
                    + Добавить занятие
                </button>
            </form>
        </div>
        
        <h2 style="margin-bottom: 1.5rem; color: #1A2B4C;">Расписание</h2>
        
        <?php if (empty($schedule)): ?>
            <div style="background: #F8FAFE; border-radius: 20px; padding: 3rem; text-align: center; color: #6c757d;">
                Занятий пока нет. Добавьте первое занятие!
            </div>
        <?php else: ?>
            <div class="admin-schedule-table">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Дата</th>
                            <th>Время</th>
                            <th>Группа</th>
                            <th>Предмет</th>
                            <th>Преподаватель</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($schedule as $row): ?>
                            <tr>
                                <form method="POST" style="display: contents;">
                                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                    <td><?= $row['id'] ?></td>
                                    <td>
                                        <input type="date" name="date" value="<?= $row['date'] ?>" required>
                                    </td>
                                    <td>
                                        <input type="time" name="time" value="<?= $row['time'] ?>" required>
                                    </td>
                                    <td>
                                        <select name="group_name" required>
                                            <option value="Оператор ЧПУ-1 (дневная)" <?= $row['group_name'] == 'Оператор ЧПУ-1 (дневная)' ? 'selected' : '' ?>>Оператор ЧПУ-1 (дневная)</option>
                                            <option value="Контролер ОТК-3 (вечерняя)" <?= $row['group_name'] == 'Контролер ОТК-3 (вечерняя)' ? 'selected' : '' ?>>Контролер ОТК-3 (вечерняя)</option>
                                            <option value="Наладчик ЧПУ-5 (субботняя)" <?= $row['group_name'] == 'Наладчик ЧПУ-5 (субботняя)' ? 'selected' : '' ?>>Наладчик ЧПУ-5 (субботняя)</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" name="subject" value="<?= sanitize($row['subject']) ?>" required>
                                    </td>
                                    <td>
                                        <input type="text" name="teacher" value="<?= sanitize($row['teacher']) ?>" required>
                                    </td>
                                    <td style="white-space: nowrap;">
                                        <button type="submit" name="edit_schedule" class="btn-edit" style="padding: 0.4rem 0.8rem; font-size: 0.85rem;">
                                            Сохранить
                                        </button>
                                        <button type="submit" name="delete_schedule" class="btn-delete" style="padding: 0.4rem 0.8rem; font-size: 0.85rem;" onclick="return confirm('Удалить запись?')">
                                            Удалить
                                        </button>
                                    </td>
                                </form>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
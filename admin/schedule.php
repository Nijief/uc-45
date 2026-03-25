<?php
    require_once 'auth_check.php';
    include '../includes/header.php';

    // Добавление записи
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

    // Редактирование
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

    // Удаление
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_schedule'])) {
        $id = $_POST['id'];
        $stmt = $pdo->prepare("DELETE FROM schedule WHERE id = ?");
        $stmt->execute([$id]);
        header('Location: schedule.php');
        exit;
    }

    $schedule = getSchedule($pdo);
?>

<div class="container">
    <h1>Управление расписанием</h1>
    
    <div class="admin-section" style="background: #F8FAFE; padding: 2rem; border-radius: 20px; margin-bottom: 2rem;">
        <h2>Добавить занятие</h2>
        <form method="POST">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                <div class="form-group">
                    <label>Дата</label>
                    <input type="date" name="date" required>
                </div>
                <div class="form-group">
                    <label>Время</label>
                    <input type="time" name="time" required>
                </div>
                <div class="form-group">
                    <label>Группа</label>
                    <select name="group_name" required>
                        <option value="КИП-01">КИП-01</option>
                        <option value="АСУ-05">АСУ-05</option>
                        <option value="ПРОГ-22">ПРОГ-22</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Предмет</label>
                    <input type="text" name="subject" required>
                </div>
                <div class="form-group">
                    <label>Преподаватель</label>
                    <input type="text" name="teacher" required>
                </div>
            </div>
            <button type="submit" name="add_schedule" class="btn" style="margin-top: 1rem;">Добавить занятие</button>
        </form>
    </div>
    
    <h2>Расписание</h2>
    <div class="schedule-wrapper">
        <table class="schedule-table">
            <thead>
                <tr><th>ID</th><th>Дата</th><th>Время</th><th>Группа</th><th>Предмет</th><th>Преподаватель</th><th>Действия</th></tr>
            </thead>
            <tbody>
                <?php foreach ($schedule as $row): ?>
                    <tr>
                        <form method="POST" style="display: contents;">
                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                            <td><?= $row['id'] ?></td>
                            <td><input type="date" name="date" value="<?= $row['date'] ?>" style="width: 120px;"></td>
                            <td><input type="time" name="time" value="<?= $row['time'] ?>" style="width: 80px;"></td>
                            <td>
                                <select name="group_name" style="width: 100px;">
                                    <option value="КИП-01" <?= $row['group_name'] == 'КИП-01' ? 'selected' : '' ?>>КИП-01</option>
                                    <option value="АСУ-05" <?= $row['group_name'] == 'АСУ-05' ? 'selected' : '' ?>>АСУ-05</option>
                                    <option value="ПРОГ-22" <?= $row['group_name'] == 'ПРОГ-22' ? 'selected' : '' ?>>ПРОГ-22</option>
                                </select>
                            </td>
                            <td><input type="text" name="subject" value="<?= sanitize($row['subject']) ?>" style="width: 150px;"></td>
                            <td><input type="text" name="teacher" value="<?= sanitize($row['teacher']) ?>" style="width: 150px;"></td>
                            <td>
                                <button type="submit" name="edit_schedule" style="background: #28a745; color: white; border: none; padding: 0.25rem 0.5rem; border-radius: 4px; cursor: pointer;">Сохранить</button>
                                <button type="submit" name="delete_schedule" style="background: #dc3545; color: white; border: none; padding: 0.25rem 0.5rem; border-radius: 4px; cursor: pointer;" onclick="return confirm('Удалить запись?')">Удалить</button>
                            </td>
                        </form>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
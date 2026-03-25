<?php
    require_once 'auth_check.php';
    include '../includes/header.php';

    // Добавление программы
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_program'])) {
        $title = $_POST['title'];
        $duration = $_POST['duration'];
        $requirements = $_POST['requirements'];
        $curriculum = $_POST['curriculum'];
        $description = $_POST['description'];
        $image = '';

        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../assets/uploads/';
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            $fileType = $_FILES['image']['type'];

            if (in_array($fileType, $allowedTypes) && $_FILES['image']['size'] <= 2 * 1024 * 1024) {
                $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $image = uniqid() . '.' . $ext;
                move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $image);
            }
        }

        $stmt = $pdo->prepare("INSERT INTO programs (title, duration, requirements, curriculum, description, image) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $duration, $requirements, $curriculum, $description, $image]);
        header('Location: programs.php');
        exit;
    }

    // Редактирование
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_program'])) {
        $id = $_POST['id'];
        $title = $_POST['title'];
        $duration = $_POST['duration'];
        $requirements = $_POST['requirements'];
        $curriculum = $_POST['curriculum'];
        $description = $_POST['description'];
        $image = $_POST['current_image'];

        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../assets/uploads/';
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            $fileType = $_FILES['image']['type'];

            if (in_array($fileType, $allowedTypes) && $_FILES['image']['size'] <= 2 * 1024 * 1024) {
                if ($image && file_exists($uploadDir . $image)) {
                    unlink($uploadDir . $image);
                }
                $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $image = uniqid() . '.' . $ext;
                move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $image);
            }
        }

        $stmt = $pdo->prepare("UPDATE programs SET title = ?, duration = ?, requirements = ?, curriculum = ?, description = ?, image = ? WHERE id = ?");
        $stmt->execute([$title, $duration, $requirements, $curriculum, $description, $image, $id]);
        header('Location: programs.php');
        exit;
    }

    // Удаление
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_program'])) {
        $id = $_POST['id'];
        $stmt = $pdo->prepare("SELECT image FROM programs WHERE id = ?");
        $stmt->execute([$id]);
        $program = $stmt->fetch();
        
        if ($program && $program['image'] && file_exists('../assets/uploads/' . $program['image'])) {
            unlink('../assets/uploads/' . $program['image']);
        }
        
        $stmt = $pdo->prepare("DELETE FROM programs WHERE id = ?");
        $stmt->execute([$id]);
        header('Location: programs.php');
        exit;
    }

    $programs = getPrograms($pdo);
?>

<div class="container">
    <h1>Управление образовательными программами</h1>
    
    <div class="admin-section" style="background: #F8FAFE; padding: 2rem; border-radius: 20px; margin-bottom: 2rem;">
        <h2>Добавить программу</h2>
        <form method="POST" enctype="multipart/form-data">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1rem;">
                <div class="form-group">
                    <label>Название программы</label>
                    <input type="text" name="title" required>
                </div>
                <div class="form-group">
                    <label>Длительность</label>
                    <input type="text" name="duration" placeholder="72 часа" required>
                </div>
                <div class="form-group">
                    <label>Требования к слушателям</label>
                    <input type="text" name="requirements" placeholder="Среднее профессиональное образование" required>
                </div>
                <div class="form-group">
                    <label>Учебный план (кратко)</label>
                    <input type="text" name="curriculum" placeholder="Основные темы курса" required>
                </div>
                <div class="form-group">
                    <label>Изображение</label>
                    <input type="file" name="image">
                </div>
            </div>
            <div class="form-group">
                <label>Полное описание</label>
                <textarea name="description" rows="4" required></textarea>
            </div>
            <button type="submit" name="add_program" class="btn">Добавить программу</button>
        </form>
    </div>
    
    <h2>Существующие программы</h2>
    <div class="cards-grid">
        <?php foreach ($programs as $program): ?>
            <div class="card">
                <div class="card-image">
                    <?php if ($program['image']): ?>
                        <img src="<?= SITE_URL ?>/assets/uploads/<?= sanitize($program['image']) ?>" alt="<?= sanitize($program['title']) ?>">
                    <?php else: ?>
                        <div class="card-image-placeholder">📘</div>
                    <?php endif; ?>
                </div>
                <div class="card-content">
                    <h3><?= sanitize($program['title']) ?></h3>
                    <p><strong>Длительность:</strong> <?= sanitize($program['duration']) ?></p>
                    <details>
                        <summary>Подробнее</summary>
                        <p><strong>Требования:</strong> <?= sanitize($program['requirements']) ?></p>
                        <p><strong>Учебный план:</strong> <?= sanitize($program['curriculum']) ?></p>
                        <p><strong>Описание:</strong> <?= sanitize(mb_substr($program['description'], 0, 100)) ?>...</p>
                    </details>
                    
                    <details style="margin: 1rem 0;">
                        <summary style="color: #007BFF; cursor: pointer;">Редактировать</summary>
                        <form method="POST" enctype="multipart/form-data" style="margin-top: 1rem;">
                            <input type="hidden" name="id" value="<?= $program['id'] ?>">
                            <input type="hidden" name="current_image" value="<?= sanitize($program['image']) ?>">
                            <div class="form-group">
                                <label>Название</label>
                                <input type="text" name="title" value="<?= sanitize($program['title']) ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Длительность</label>
                                <input type="text" name="duration" value="<?= sanitize($program['duration']) ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Требования</label>
                                <input type="text" name="requirements" value="<?= sanitize($program['requirements']) ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Учебный план</label>
                                <input type="text" name="curriculum" value="<?= sanitize($program['curriculum']) ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Описание</label>
                                <textarea name="description" rows="3" required><?= sanitize($program['description']) ?></textarea>
                            </div>
                            <div class="form-group">
                                <label>Новое изображение (оставьте пустым, чтобы не менять)</label>
                                <input type="file" name="image">
                            </div>
                            <button type="submit" name="edit_program" class="btn-outline" style="padding: 0.5rem 1rem;">Сохранить изменения</button>
                        </form>
                    </details>
                    
                    <form method="POST" onsubmit="return confirm('Удалить программу?')">
                        <input type="hidden" name="id" value="<?= $program['id'] ?>">
                        <button type="submit" name="delete_program" style="background: #dc3545; color: white; border: none; padding: 0.5rem 1rem; border-radius: 8px; cursor: pointer;">Удалить</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
<?php
    require_once 'auth_check.php';
    include '../includes/header.php';

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

<link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/admin.css">

<div class="admin-container">
    <div class="admin-dashboard">
        <div class="admin-page-header">
            <h1>Управление образовательными программами</h1>
            <p>Добавление, редактирование и удаление образовательных программ</p>
        </div>
        
        <div class="admin-form-section">
            <h2>Добавить программу</h2>
            <form method="POST" enctype="multipart/form-data">
                <div class="admin-form-row">
                    <div class="admin-form-group">
                        <label>Название программы</label>
                        <input type="text" name="title" required>
                    </div>
                    <div class="admin-form-group">
                        <label>Длительность</label>
                        <input type="text" name="duration" placeholder="72 часа" required>
                    </div>
                </div>
                
                <div class="admin-form-row">
                    <div class="admin-form-group">
                        <label>Требования к слушателям</label>
                        <input type="text" name="requirements" placeholder="Среднее профессиональное образование" required>
                    </div>
                    <div class="admin-form-group">
                        <label>Учебный план (кратко)</label>
                        <input type="text" name="curriculum" placeholder="Основные темы курса" required>
                    </div>
                </div>
                
                <div class="admin-form-row">
                    <div class="admin-form-group">
                        <label>Изображение</label>
                        <input type="file" name="image">
                    </div>
                </div>
                
                <div class="admin-form-group">
                    <label>Полное описание</label>
                    <textarea name="description" rows="4" required></textarea>
                </div>
                
                <button type="submit" name="add_program" class="btn-save">
                    + Добавить программу
                </button>
            </form>
        </div>

        <h2 style="margin-bottom: 1.5rem; color: #1A2B4C;">Существующие программы</h2>
        <div class="admin-card-grid">
            <?php foreach ($programs as $program): ?>
                <div class="admin-card">
                    <div class="admin-card-image">
                        <?php if ($program['image']): ?>
                            <img src="<?= SITE_URL ?>/assets/uploads/<?= sanitize($program['image']) ?>" alt="<?= sanitize($program['title']) ?>">
                        <?php else: ?>
                            <div class="admin-card-image-placeholder"></div>
                        <?php endif; ?>
                    </div>
                    <div class="admin-card-content">
                        <div class="admin-card-title"><?= sanitize($program['title']) ?></div>
                        <div class="admin-card-meta">
                            <span>⏱<?= sanitize($program['duration']) ?></span>
                        </div>
                        
                        <details class="admin-details">
                            <summary>Подробнее</summary>
                            <div class="admin-details-content">
                                <p><strong>Требования:</strong> <?= sanitize($program['requirements']) ?></p>
                                <p><strong>Учебный план:</strong> <?= sanitize($program['curriculum']) ?></p>
                                <p><strong>Описание:</strong> <?= sanitize(mb_substr($program['description'], 0, 100)) ?>...</p>
                            </div>
                        </details>
                        
                        <details class="admin-details">
                            <summary>Редактировать</summary>
                            <div class="admin-details-content">
                                <form method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="id" value="<?= $program['id'] ?>">
                                    <input type="hidden" name="current_image" value="<?= sanitize($program['image']) ?>">
                                    
                                    <div class="admin-form-group">
                                        <label>Название</label>
                                        <input type="text" name="title" value="<?= sanitize($program['title']) ?>" required>
                                    </div>
                                    
                                    <div class="admin-form-group">
                                        <label>Длительность</label>
                                        <input type="text" name="duration" value="<?= sanitize($program['duration']) ?>" required>
                                    </div>
                                    
                                    <div class="admin-form-group">
                                        <label>Требования</label>
                                        <input type="text" name="requirements" value="<?= sanitize($program['requirements']) ?>" required>
                                    </div>
                                    
                                    <div class="admin-form-group">
                                        <label>Учебный план</label>
                                        <input type="text" name="curriculum" value="<?= sanitize($program['curriculum']) ?>" required>
                                    </div>
                                    
                                    <div class="admin-form-group">
                                        <label>Описание</label>
                                        <textarea name="description" rows="3" required><?= sanitize($program['description']) ?></textarea>
                                    </div>
                                    
                                    <div class="admin-form-group">
                                        <label>Новое изображение (оставьте пустым, чтобы не менять)</label>
                                        <input type="file" name="image">
                                    </div>
                                    
                                    <button type="submit" name="edit_program" class="btn-edit">
                                        Сохранить изменения
                                    </button>
                                </form>
                            </div>
                        </details>
                        
                        <div class="admin-card-actions">
                            <form method="POST" onsubmit="return confirm('Удалить программу?')" style="display: inline;">
                                <input type="hidden" name="id" value="<?= $program['id'] ?>">
                                <button type="submit" name="delete_program" class="btn-delete">
                                    Удалить
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
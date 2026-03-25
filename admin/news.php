<?php
    require_once 'auth_check.php';
    include '../includes/header.php';

    // Добавление новости
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_news'])) {
        $title = $_POST['title'];
        $content = $_POST['content'];
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

        $stmt = $pdo->prepare("INSERT INTO news (title, content, image) VALUES (?, ?, ?)");
        $stmt->execute([$title, $content, $image]);
        header('Location: news.php');
        exit;
    }

    // Редактирование новости
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_news'])) {
        $id = $_POST['id'];
        $title = $_POST['title'];
        $content = $_POST['content'];
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

        $stmt = $pdo->prepare("UPDATE news SET title = ?, content = ?, image = ? WHERE id = ?");
        $stmt->execute([$title, $content, $image, $id]);
        header('Location: news.php');
        exit;
    }

    // Удаление новости (POST для безопасности)
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_news'])) {
        $id = $_POST['id'];
        $stmt = $pdo->prepare("SELECT image FROM news WHERE id = ?");
        $stmt->execute([$id]);
        $news = $stmt->fetch();
        
        if ($news && $news['image'] && file_exists('../assets/uploads/' . $news['image'])) {
            unlink('../assets/uploads/' . $news['image']);
        }
        
        $stmt = $pdo->prepare("DELETE FROM news WHERE id = ?");
        $stmt->execute([$id]);
        header('Location: news.php');
        exit;
    }

    $news = getNews($pdo);
?>

<link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/admin.css">

<div class="admin-container">
    <div class="admin-dashboard">
        <!-- Заголовок страницы -->
        <div class="admin-page-header">
            <h1>Управление новостями</h1>
            <p>Добавление, редактирование и удаление новостей</p>
        </div>
        
        <!-- Форма добавления новости -->
        <div class="admin-form-section">
            <h2>Добавить новость</h2>
            <form method="POST" enctype="multipart/form-data">
                <div class="admin-form-group">
                    <label>Заголовок</label>
                    <input type="text" name="title" required>
                </div>
                <div class="admin-form-group">
                    <label>Текст новости</label>
                    <textarea name="content" rows="6" required></textarea>
                </div>
                <div class="admin-form-group">
                    <label>Изображение (jpg, png, gif, webp, до 2МБ)</label>
                    <input type="file" name="image">
                </div>
                <button type="submit" name="add_news" class="btn-save">➕ Добавить новость</button>
            </form>
        </div>
        
        <!-- Список существующих новостей -->
        <h2 style="margin-bottom: 1.5rem; color: #1A2B4C;">Существующие новости</h2>
        <div class="admin-card-grid">
            <?php foreach ($news as $item): ?>
                <div class="admin-card">
                    <div class="admin-card-image">
                        <?php if ($item['image']): ?>
                            <img src="<?= SITE_URL ?>/assets/uploads/<?= sanitize($item['image']) ?>" alt="<?= sanitize($item['title']) ?>">
                        <?php else: ?>
                            <div class="admin-card-image-placeholder">📰</div>
                        <?php endif; ?>
                    </div>
                    <div class="admin-card-content">
                        <div class="admin-card-title"><?= sanitize($item['title']) ?></div>
                        <div class="admin-card-meta">
                            <span>📅 <?= date('d.m.Y', strtotime($item['created_at'])) ?></span>
                        </div>
                        
                        <!-- Просмотр текста -->
                        <details class="admin-details">
                            <summary>Просмотр текста</summary>
                            <div class="admin-details-content">
                                <p><?= sanitize(mb_substr($item['content'], 0, 150)) ?>...</p>
                            </div>
                        </details>
                        
                        <!-- Форма редактирования -->
                        <details class="admin-details">
                            <summary>Редактировать</summary>
                            <div class="admin-details-content">
                                <form method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="id" value="<?= $item['id'] ?>">
                                    <input type="hidden" name="current_image" value="<?= sanitize($item['image']) ?>">
                                    
                                    <div class="admin-form-group">
                                        <label>Заголовок</label>
                                        <input type="text" name="title" value="<?= sanitize($item['title']) ?>" required>
                                    </div>
                                    <div class="admin-form-group">
                                        <label>Текст</label>
                                        <textarea name="content" rows="4" required><?= sanitize($item['content']) ?></textarea>
                                    </div>
                                    <div class="admin-form-group">
                                        <label>Новое изображение (оставьте пустым, чтобы не менять)</label>
                                        <input type="file" name="image">
                                    </div>
                                    <button type="submit" name="edit_news" class="btn-edit">
                                        ✏️ Сохранить изменения
                                    </button>
                                </form>
                            </div>
                        </details>
                        
                        <!-- Форма удаления -->
                        <div class="admin-card-actions">
                            <form method="POST" onsubmit="return confirm('Удалить новость?')" style="display: inline;">
                                <input type="hidden" name="id" value="<?= $item['id'] ?>">
                                <button type="submit" name="delete_news" class="btn-delete">
                                    🗑️ Удалить
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
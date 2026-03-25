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

<div class="container">
    <h1>Управление новостями</h1>
    
    <div class="admin-section" style="background: #F8FAFE; padding: 2rem; border-radius: 20px; margin-bottom: 2rem;">
        <h2>Добавить новость</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Заголовок</label>
                <input type="text" name="title" required>
            </div>
            <div class="form-group">
                <label>Текст новости</label>
                <textarea name="content" rows="6" required></textarea>
            </div>
            <div class="form-group">
                <label>Изображение (jpg, png, gif, webp, до 2МБ)</label>
                <input type="file" name="image">
            </div>
            <button type="submit" name="add_news" class="btn">Добавить</button>
        </form>
    </div>
    
    <h2>Существующие новости</h2>
    <div class="cards-grid">
        <?php foreach ($news as $item): ?>
            <div class="card">
                <div class="card-image">
                    <?php if ($item['image']): ?>
                        <img src="<?= SITE_URL ?>/assets/uploads/<?= sanitize($item['image']) ?>" alt="<?= sanitize($item['title']) ?>">
                    <?php endif; ?>
                </div>
                <div class="card-content">
                    <h3><?= sanitize($item['title']) ?></h3>
                    <div class="date"><?= date('d.m.Y', strtotime($item['created_at'])) ?></div>
                    <details>
                        <summary>Просмотр текста</summary>
                        <p style="margin-top: 0.5rem;"><?= sanitize(mb_substr($item['content'], 0, 150)) ?>...</p>
                    </details>
                    
                    <!-- Форма редактирования -->
                    <details style="margin: 1rem 0;">
                        <summary style="color: #007BFF; cursor: pointer;">Редактировать</summary>
                        <form method="POST" enctype="multipart/form-data" style="margin-top: 1rem;">
                            <input type="hidden" name="id" value="<?= $item['id'] ?>">
                            <input type="hidden" name="current_image" value="<?= sanitize($item['image']) ?>">
                            <div class="form-group">
                                <label>Заголовок</label>
                                <input type="text" name="title" value="<?= sanitize($item['title']) ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Текст</label>
                                <textarea name="content" rows="4" required><?= sanitize($item['content']) ?></textarea>
                            </div>
                            <div class="form-group">
                                <label>Новое изображение (оставьте пустым, чтобы не менять)</label>
                                <input type="file" name="image">
                            </div>
                            <button type="submit" name="edit_news" class="btn-outline" style="padding: 0.5rem 1rem;">Сохранить изменения</button>
                        </form>
                    </details>
                    
                    <!-- Форма удаления -->
                    <form method="POST" onsubmit="return confirm('Удалить новость?')">
                        <input type="hidden" name="id" value="<?= $item['id'] ?>">
                        <button type="submit" name="delete_news" style="background: #dc3545; color: white; border: none; padding: 0.5rem 1rem; border-radius: 8px; cursor: pointer;">Удалить</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
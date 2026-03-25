<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

if (isAdmin()) {
    header('Location: index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_id'] = $user['id'];
        header('Location: index.php');
        exit;
    } else {
        $error = 'Неверный логин или пароль';
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход в админ-панель | Учебный центр</title>
    <link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/admin.css">
</head>
<body>
    <div class="admin-login-page">
        <div class="admin-login-card">
            <div class="admin-login-header">
                <div class="admin-login-logo">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6-4h12m-12 0a2 2 0 01-2-2V6a2 2 0 012-2h12a2 2 0 012 2v6a2 2 0 01-2 2H6z"></path>
                    </svg>
                </div>
                <h1>Добро пожаловать</h1>
                <p>Войдите в панель управления Учебного центра</p>
            </div>
            
            <?php if ($error): ?>
                <div class="error-message">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" class="admin-login-form">
                <div class="form-group">
                    <label for="username">Логин</label>
                    <input type="text" name="username" id="username" placeholder="admin" required>
                </div>
                <div class="form-group">
                    <label for="password">Пароль</label>
                    <input type="password" name="password" id="password" placeholder="••••••••" required>
                </div>
                <button type="submit" class="btn admin-login-btn">Войти в панель управления</button>
            </form>
            
            <div class="admin-back-link">
                <a href="<?= SITE_URL ?>/index.php">← Вернуться на сайт</a>
            </div>
        </div>
    </div>
</body>
</html>